<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Part;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_is_available(): void
    {
        $response = $this->get(route('main.index'));

        $response->assertOk();
    }

    public function test_storefront_pages_are_available(): void
    {
        $part = Part::query()->create([
            'name' => 'Тестовая запчасть',
            'sku' => 'SF-PART-001',
            'price' => 1990,
            'stock' => 7,
            'is_active' => true,
        ]);

        $this->get(route('storefront.index'))->assertOk();
        $this->get(route('storefront.catalog'))->assertOk();
        $this->get(route('storefront.category', ['category' => 1]))->assertOk();
        $this->get(route('storefront.product', ['part' => $part->id]))->assertOk();
        $this->get(route('storefront.cart'))->assertOk();
        $this->get(route('storefront.catalog-data'))->assertOk();
        $this->get(route('storefront.part-data', $part))->assertOk();
    }

    public function test_inactive_part_data_is_not_available_on_storefront_api(): void
    {
        $part = Part::query()->create([
            'name' => 'Скрытая запчасть',
            'sku' => 'SF-PART-002',
            'price' => 2990,
            'stock' => 3,
            'is_active' => false,
        ]);

        $this->get(route('storefront.part-data', $part))->assertNotFound();
    }

    public function test_category_crud_flow_works(): void
    {
        $createResponse = $this->post(route('category.store'), [
            'title' => 'Тестовая категория',
        ]);

        $createResponse->assertRedirect();
        $this->assertDatabaseHas('categories', ['title' => 'Тестовая категория']);

        $category = Category::query()->where('title', 'Тестовая категория')->firstOrFail();

        $updateResponse = $this->patch(route('category.update', $category), [
            'title' => 'Обновленная категория',
        ]);

        $updateResponse->assertRedirect(route('category.show', $category));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'title' => 'Обновленная категория']);

        $deleteResponse = $this->delete(route('category.delete', $category));

        $deleteResponse->assertRedirect(route('category.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_part_crud_flow_works(): void
    {
        $category = Category::query()->create(['title' => 'Фильтры']);

        $createResponse = $this->post(route('part.store'), [
            'name' => 'Фильтр салона',
            'sku' => 'FLT-CAB-777',
            'brand' => 'MANN',
            'category_id' => $category->id,
            'price' => 1190,
            'stock' => 12,
            'description' => 'Тестовая позиция',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect();
        $this->assertDatabaseHas('parts', ['sku' => 'FLT-CAB-777', 'name' => 'Фильтр салона']);

        $part = Part::query()->where('sku', 'FLT-CAB-777')->firstOrFail();

        $updateResponse = $this->patch(route('part.update', $part), [
            'name' => 'Фильтр салона угольный',
            'sku' => 'FLT-CAB-777',
            'brand' => 'MANN',
            'category_id' => $category->id,
            'price' => 1390,
            'stock' => 5,
            'description' => 'Обновленная позиция',
        ]);

        $updateResponse->assertRedirect(route('part.show', $part));
        $this->assertDatabaseHas('parts', [
            'id' => $part->id,
            'name' => 'Фильтр салона угольный',
            'stock' => 5,
            'is_active' => 0,
        ]);

        $deleteResponse = $this->delete(route('part.delete', $part));

        $deleteResponse->assertRedirect(route('part.index'));
        $this->assertDatabaseMissing('parts', ['id' => $part->id]);
    }
}
