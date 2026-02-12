const categoryPalette = ['#1e3a5f', '#155e75', '#166534', '#92400e', '#7c2d12', '#6b21a8'];
const productPalette = ['#0f4c81', '#0f766e', '#b45309', '#7c3aed', '#b91c1c', '#2563eb'];

const encodeSvg = (svg) => `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`;

const placeholder = (text, bg, sub = '') => {
  const safeText = String(text || '').slice(0, 28);
  const safeSub = String(sub || '').slice(0, 28);

  const svg = `
<svg xmlns='http://www.w3.org/2000/svg' width='800' height='520' viewBox='0 0 800 520'>
  <defs>
    <linearGradient id='g' x1='0' y1='0' x2='1' y2='1'>
      <stop offset='0%' stop-color='${bg}' />
      <stop offset='100%' stop-color='#0b1d33' />
    </linearGradient>
  </defs>
  <rect fill='url(#g)' width='800' height='520' rx='24' />
  <circle cx='710' cy='90' r='120' fill='rgba(255,255,255,0.08)' />
  <circle cx='110' cy='450' r='170' fill='rgba(255,255,255,0.08)' />
  <text x='56' y='280' font-size='52' font-family='Segoe UI, Arial' fill='white' font-weight='700'>${safeText}</text>
  <text x='56' y='330' font-size='28' font-family='Segoe UI, Arial' fill='rgba(255,255,255,0.78)'>${safeSub}</text>
</svg>`;

  return encodeSvg(svg);
};

export const buildCategoryImage = (category) => {
  const id = Number(category.id || 0);
  const color = categoryPalette[id % categoryPalette.length];
  return placeholder(category.title, color, 'Категория');
};

export const buildProductImages = (product) => {
  const id = Number(product.id || 0);
  const base = productPalette[id % productPalette.length];

  return [
    placeholder(product.name, base, product.brand || product.sku),
    placeholder(product.name, '#1f6f8f', 'Auto Parts Shop'),
    placeholder(product.name, '#274c77', product.category || 'Деталь'),
  ];
};
