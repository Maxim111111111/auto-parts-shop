import { computed, ref } from 'vue';

const user = ref(null);
const loading = ref(false);
const loaded = ref(false);

const readCsrfToken = () => {
  if (typeof document === 'undefined') return '';
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
};

export const useAuth = () => {
  const ensureLoaded = async () => {
    if (loading.value || loaded.value) return;

    loading.value = true;

    try {
      const response = await fetch('/auth/me', {
        headers: { Accept: 'application/json' },
      });

      if (!response.ok) {
        user.value = null;
        loaded.value = true;
        return;
      }

      const data = await response.json();
      user.value = data.authenticated ? data.user : null;
      loaded.value = true;
    } catch {
      user.value = null;
      loaded.value = true;
    } finally {
      loading.value = false;
    }
  };

  const logout = async () => {
    await fetch('/auth/logout', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'X-CSRF-TOKEN': readCsrfToken(),
      },
    });

    user.value = null;
    loaded.value = true;
  };

  return {
    user,
    loading,
    loaded,
    isAuthenticated: computed(() => user.value !== null),
    isAdmin: computed(() => user.value?.is_admin === true),
    ensureLoaded,
    logout,
  };
};
