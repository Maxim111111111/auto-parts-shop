import { ref } from 'vue';
import { buildCategoryImage, buildProductImages } from '../utils/media';

const loading = ref(false);
const loaded = ref(false);
const error = ref('');
const categories = ref([]);
const products = ref([]);

const normalize = (value) => Number(value || 0);

const deriveBadge = (raw) => {
  if (raw.badge) return raw.badge;

  const stock = normalize(raw.stock);
  if (stock <= 3) return 'Хит';
  if (stock >= 15) return 'Новинка';
  return '';
};

const transformProduct = (raw) => {
  const images = buildProductImages(raw);

  return {
    id: normalize(raw.id),
    categoryId: normalize(raw.category_id),
    sku: raw.sku || '',
    name: raw.name || 'Без названия',
    brand: raw.brand || '',
    price: Number(raw.price || 0),
    stock: normalize(raw.stock),
    description: raw.description || 'Качественная автозапчасть для ежедневной эксплуатации.',
    category: raw.category || 'Без категории',
    badge: deriveBadge(raw),
    images,
    image: images[0],
  };
};

const enrichCategories = (rawCategories, preparedProducts) => {
  const counters = preparedProducts.reduce((acc, item) => {
    acc[item.categoryId] = (acc[item.categoryId] || 0) + 1;
    return acc;
  }, {});

  return (rawCategories || []).map((category) => ({
    id: normalize(category.id),
    title: category.title || 'Категория',
    image: buildCategoryImage(category),
    count: counters[normalize(category.id)] || 0,
  }));
};

export const useCatalog = () => {
  const ensureLoaded = async () => {
    if (loaded.value || loading.value) return;

    loading.value = true;
    error.value = '';

    try {
      const response = await fetch('/catalog-data', {
        headers: { Accept: 'application/json' },
      });

      if (!response.ok) {
        throw new Error(`Catalog request failed: ${response.status}`);
      }

      const data = await response.json();
      const preparedProducts = (data.parts || []).map(transformProduct);

      products.value = preparedProducts;
      categories.value = enrichCategories(data.categories || [], preparedProducts);
      loaded.value = true;
    } catch (err) {
      error.value = 'Не удалось загрузить каталог.';
      console.error(err);
    } finally {
      loading.value = false;
    }
  };

  const findProduct = (id) => products.value.find((item) => item.id === normalize(id));
  const findCategory = (id) => categories.value.find((item) => item.id === normalize(id));

  return {
    loading,
    loaded,
    error,
    categories,
    products,
    ensureLoaded,
    findProduct,
    findCategory,
  };
};
