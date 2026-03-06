<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Part;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'is_admin' => true,
        ]);
    }

    public function test_dashboard_requires_admin_role(): void
    {
        $this->get(route('main.index'))->assertRedirect(route('login'));

        $user = User::query()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'secret123',
            'is_admin' => false,
        ]);

        $this->actingAs($user)->get(route('main.index'))->assertForbidden();

        $this->actingAs($this->createAdmin())->get(route('main.index'))->assertOk();
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

    public function test_non_admin_cannot_access_admin_sections(): void
    {
        $user = User::query()->create([
            'name' => 'User',
            'email' => 'member@example.com',
            'password' => 'secret123',
            'is_admin' => false,
        ]);

        $this->actingAs($user)->get(route('category.index'))->assertForbidden();
        $this->actingAs($user)->get(route('part.index'))->assertForbidden();
    }

    public function test_category_crud_flow_works(): void
    {
        $admin = $this->createAdmin();

        $createResponse = $this->actingAs($admin)->post(route('category.store'), [
            'title' => 'Тестовая категория',
        ]);

        $createResponse->assertRedirect();
        $this->assertDatabaseHas('categories', ['title' => 'Тестовая категория']);

        $category = Category::query()->where('title', 'Тестовая категория')->firstOrFail();

        $updateResponse = $this->actingAs($admin)->patch(route('category.update', $category), [
            'title' => 'Обновленная категория',
        ]);

        $updateResponse->assertRedirect(route('category.show', $category));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'title' => 'Обновленная категория']);

        $deleteResponse = $this->actingAs($admin)->delete(route('category.delete', $category));

        $deleteResponse->assertRedirect(route('category.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_part_crud_flow_works(): void
    {
        $admin = $this->createAdmin();
        $category = Category::query()->create(['title' => 'Фильтры']);

        $createResponse = $this->actingAs($admin)->post(route('part.store'), [
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

        $updateResponse = $this->actingAs($admin)->patch(route('part.update', $part), [
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

        $deleteResponse = $this->actingAs($admin)->delete(route('part.delete', $part));

        $deleteResponse->assertRedirect(route('part.index'));
        $this->assertDatabaseMissing('parts', ['id' => $part->id]);
    }
}
