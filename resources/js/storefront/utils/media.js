const LIBRARY = {
  brake: [
    '/images/storefront/brake-disc.jpg',
    '/images/storefront/engine-bay.jpg',
    '/images/storefront/engine-detail.jpg',
  ],
  suspension: [
    '/images/storefront/suspension.jpg',
    '/images/storefront/engine-bay.jpg',
    '/images/storefront/brake-disc.jpg',
  ],
  engine: [
    '/images/storefront/engine-bay.jpg',
    '/images/storefront/engine-detail.jpg',
    '/images/storefront/air-filter.jpg',
  ],
  filter: [
    '/images/storefront/air-filter.jpg',
    '/images/storefront/engine-bay.jpg',
    '/images/storefront/engine-detail.jpg',
  ],
  electrical: [
    '/images/storefront/battery.jpg',
    '/images/storefront/engine-detail.jpg',
    '/images/storefront/engine-bay.jpg',
  ],
  default: [
    '/images/storefront/engine-bay.jpg',
    '/images/storefront/air-filter.jpg',
    '/images/storefront/brake-disc.jpg',
  ],
};

const GROUPS = [
  { key: 'brake', match: ['тормоз', 'brake', 'колод', 'disc', 'rotor', 'brk-'] },
  { key: 'suspension', match: ['подвес', 'suspension', 'shock', 'амортиз', 'sus-'] },
  { key: 'engine', match: ['двиг', 'engine', 'motor', 'грм', 'belt', 'eng-'] },
  { key: 'filter', match: ['фильтр', 'filter', 'air filter', 'oil filter', 'flt-'] },
  { key: 'electrical', match: ['электр', 'ignition', 'spark', 'battery', 'elc-'] },
];

const normalize = (value) => String(value || '').toLowerCase();

const detectGroup = (input) => {
  const text = normalize(input);

  for (const group of GROUPS) {
    if (group.match.some((token) => text.includes(token))) {
      return group.key;
    }
  }

  return 'default';
};

export const buildCategoryImage = (category) => {
  const group = detectGroup(category?.title);
  return LIBRARY[group][0] || LIBRARY.default[0];
};

export const buildProductImages = (product) => {
  const group = detectGroup([product?.name, product?.brand, product?.category, product?.sku].join(' '));
  return LIBRARY[group] || LIBRARY.default;
};
