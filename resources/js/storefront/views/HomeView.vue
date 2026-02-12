<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import CategoryCard from '../components/CategoryCard.vue';
import ProductCard from '../components/ProductCard.vue';
import { useCatalog } from '../composables/useCatalog';

const { loading, error, categories, products, ensureLoaded } = useCatalog();

const promoSlides = [
  {
    title: 'Скидка до 20% на тормозную систему',
    text: 'Тормозные диски, колодки и датчики из наличия с гарантией от поставщика.',
    cta: 'Выбрать тормоза',
  },
  {
    title: 'Неделя фильтров для ТО',
    text: 'Соберите комплект для обслуживания двигателя за пару минут.',
    cta: 'Собрать комплект',
  },
  {
    title: 'Быстрая доставка по городу',
    text: 'Доставим заказ в день оформления или подготовим самовывоз за 30 минут.',
    cta: 'Смотреть каталог',
  },
];

const valueProps = [
  {
    icon: '🚚',
    title: 'Доставка день в день',
    text: 'По городу привозим в течение дня при заказе до 15:00.',
  },
  {
    icon: '✅',
    title: 'Проверенные поставщики',
    text: 'Работаем только с официальными дистрибьюторами.',
  },
  {
    icon: '🔧',
    title: 'Помощь с подбором',
    text: 'Подскажем совместимость по артикулу и категории.',
  },
  {
    icon: '💳',
    title: 'Удобная оплата',
    text: 'Наличные, карта, перевод — выбирайте привычный вариант.',
  },
];

const steps = [
  {
    title: '1. Найдите деталь',
    text: 'Используйте категории, фильтры и поиск по SKU/бренду.',
  },
  {
    title: '2. Добавьте в корзину',
    text: 'Проверьте количество и сохраните нужные позиции.',
  },
  {
    title: '3. Подтвердите заказ',
    text: 'Оставьте контакты, менеджер свяжется для подтверждения.',
  },
];

const reviews = [
  {
    author: 'Илья, Kia Rio',
    text: 'Быстро нашел нужные фильтры и тормозные колодки. Доставка в тот же день.',
  },
  {
    author: 'Сергей, Toyota Camry',
    text: 'Цены адекватные, удобно сравнивать в каталоге. Буду заказывать еще.',
  },
  {
    author: 'Андрей, автосервис',
    text: 'Нормальный выбор по подвеске и электрике, остатки в карточке совпадают.',
  },
];

const faqItems = [
  {
    q: 'Как понять, что запчасть подходит?',
    a: 'Проверьте SKU и категорию в карточке. Если сомневаетесь, напишите нам перед заказом.',
  },
  {
    q: 'Есть ли самовывоз?',
    a: 'Да, после подтверждения заказа можно забрать со склада.',
  },
  {
    q: 'Как быстро обрабатывается заказ?',
    a: 'Обычно в течение 10-20 минут в рабочее время.',
  },
];

const currentSlide = ref(0);
let timerId = null;

const featuredProducts = computed(() => products.value.slice(0, 8));
const featuredCategories = computed(() => categories.value.slice(0, 6));

const totalStock = computed(() => products.value.reduce((sum, item) => sum + item.stock, 0));
const inStockCount = computed(() => products.value.filter((item) => item.stock > 0).length);
const lowStockCount = computed(() => products.value.filter((item) => item.stock > 0 && item.stock <= 5).length);

const topBrands = computed(() => {
  const map = new Map();

  for (const item of products.value) {
    if (!item.brand) continue;
    map.set(item.brand, (map.get(item.brand) || 0) + 1);
  }

  return [...map.entries()]
    .sort((a, b) => b[1] - a[1])
    .slice(0, 6)
    .map(([title]) => title);
});

const goToSlide = (index) => {
  currentSlide.value = index;
  restartTimer();
};

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % promoSlides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + promoSlides.length) % promoSlides.length;
  restartTimer();
};

const restartTimer = () => {
  if (timerId) {
    window.clearInterval(timerId);
  }

  timerId = window.setInterval(() => {
    nextSlide();
  }, 5000);
};

onMounted(async () => {
  await ensureLoaded();
  restartTimer();
});

onUnmounted(() => {
  if (timerId) window.clearInterval(timerId);
});
</script>

<template>
  <section class="container">
    <div class="promo-slider">
      <article class="promo-slide">
        <p class="eyebrow">Акции и спецпредложения</p>
        <h1>{{ promoSlides[currentSlide].title }}</h1>
        <p>{{ promoSlides[currentSlide].text }}</p>

        <div class="promo-actions">
          <RouterLink to="/catalog" class="btn btn-primary">{{ promoSlides[currentSlide].cta }}</RouterLink>
          <RouterLink to="/cart" class="btn btn-ghost">Перейти в корзину</RouterLink>
        </div>

        <div class="promo-bottom">
          <div class="promo-dots">
            <button
              v-for="(item, index) in promoSlides"
              :key="item.title"
              type="button"
              :class="['dot', { active: index === currentSlide }]"
              @click="goToSlide(index)"
            />
          </div>

          <div class="slide-nav-wrap">
            <button type="button" class="slide-nav" @click="prevSlide">←</button>
            <button type="button" class="slide-nav" @click="nextSlide">→</button>
          </div>
        </div>
      </article>
    </div>

    <section class="trust-strip">
      <p>Популярные бренды:</p>
      <div class="trust-list" v-if="topBrands.length > 0">
        <span v-for="brand in topBrands" :key="brand" class="trust-chip">{{ brand }}</span>
      </div>
      <div v-else class="trust-list">
        <span class="trust-chip">Bosch</span>
        <span class="trust-chip">MANN</span>
        <span class="trust-chip">NGK</span>
      </div>
    </section>

    <section class="stats-grid" v-if="!loading">
      <article class="stat-box">
        <p>Категорий</p>
        <strong>{{ categories.length }}</strong>
      </article>
      <article class="stat-box">
        <p>Товаров в каталоге</p>
        <strong>{{ products.length }}</strong>
      </article>
      <article class="stat-box">
        <p>В наличии</p>
        <strong>{{ inStockCount }}</strong>
      </article>
      <article class="stat-box">
        <p>Суммарный остаток</p>
        <strong>{{ totalStock }}</strong>
      </article>
    </section>

    <section class="value-grid">
      <article v-for="item in valueProps" :key="item.title" class="value-card">
        <span class="value-icon">{{ item.icon }}</span>
        <h3>{{ item.title }}</h3>
        <p>{{ item.text }}</p>
      </article>
    </section>

    <section>
      <div class="section-head">
        <h2>Категории запчастей</h2>
        <RouterLink to="/catalog">Перейти в каталог</RouterLink>
      </div>

      <p v-if="error" class="inline-error">{{ error }}</p>
      <p v-else-if="loading" class="inline-info">Загрузка категорий...</p>

      <div v-else class="category-grid">
        <CategoryCard v-for="category in featuredCategories" :key="category.id" :category="category" />
      </div>
    </section>

    <section class="highlight-grid" v-if="!loading">
      <article class="highlight-card">
        <p class="eyebrow">Под заказ</p>
        <h3>Редкие позиции</h3>
        <p>Если товара нет в наличии, поможем привезти под заказ от поставщика.</p>
      </article>
      <article class="highlight-card">
        <p class="eyebrow">Внимание</p>
        <h3>Заканчиваются на складе</h3>
        <p>Сейчас {{ lowStockCount }} позиций с остатком до 5 штук.</p>
      </article>
    </section>

    <section>
      <div class="section-head">
        <h2>Популярные товары</h2>
        <RouterLink to="/catalog">Смотреть все</RouterLink>
      </div>

      <p v-if="loading" class="inline-info">Загрузка товаров...</p>
      <div v-else class="product-grid">
        <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
      </div>
    </section>

    <section>
      <div class="section-head">
        <h2>Как купить</h2>
      </div>

      <div class="steps-grid">
        <article v-for="item in steps" :key="item.title" class="step-card">
          <h3>{{ item.title }}</h3>
          <p>{{ item.text }}</p>
        </article>
      </div>
    </section>

    <section>
      <div class="section-head">
        <h2>Отзывы покупателей</h2>
      </div>

      <div class="reviews-grid">
        <article v-for="item in reviews" :key="item.author" class="review-card">
          <p>“{{ item.text }}”</p>
          <strong>{{ item.author }}</strong>
        </article>
      </div>
    </section>

    <section>
      <div class="section-head">
        <h2>Частые вопросы</h2>
      </div>

      <div class="faq-grid">
        <details v-for="item in faqItems" :key="item.q" class="faq-item">
          <summary>{{ item.q }}</summary>
          <p>{{ item.a }}</p>
        </details>
      </div>
    </section>

    <section class="final-cta">
      <h2>Нужны запчасти для ТО или срочного ремонта?</h2>
      <p>Открывайте каталог, выбирайте позиции и добавляйте в корзину за пару минут.</p>
      <div class="final-cta-actions">
        <RouterLink to="/catalog" class="btn btn-primary">Открыть каталог</RouterLink>
        <RouterLink to="/cart" class="btn btn-ghost">Перейти в корзину</RouterLink>
      </div>
    </section>
  </section>
</template>
