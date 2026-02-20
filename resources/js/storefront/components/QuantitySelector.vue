<script setup>
import { computed } from 'vue';

const props = defineProps({
  id: {
    type: String,
    default: '',
  },
  modelValue: {
    type: Number,
    default: 1,
  },
  min: {
    type: Number,
    default: 1,
  },
  max: {
    type: Number,
    default: Number.POSITIVE_INFINITY,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['update:modelValue']);

const normalizedValue = computed(() => {
  const value = Number(props.modelValue || props.min);
  return Math.min(props.max, Math.max(props.min, value));
});

const decrease = () => {
  if (props.disabled || normalizedValue.value <= props.min) return;
  emit('update:modelValue', normalizedValue.value - 1);
};

const increase = () => {
  if (props.disabled || normalizedValue.value >= props.max) return;
  emit('update:modelValue', normalizedValue.value + 1);
};

const updateByInput = (event) => {
  const raw = Number(event.target.value || props.min);
  const value = Math.min(props.max, Math.max(props.min, raw));
  emit('update:modelValue', value);
};
</script>

<template>
  <div class="quantity-selector" :class="{ 'is-disabled': disabled }">
    <button
      type="button"
      class="quantity-btn"
      :disabled="disabled || normalizedValue <= min"
      @click="decrease"
      aria-label="Уменьшить количество"
    >
      −
    </button>

    <input
      :id="id || null"
      class="quantity-input"
      type="number"
      :min="min"
      :max="Number.isFinite(max) ? max : null"
      :value="normalizedValue"
      :disabled="disabled"
      @input="updateByInput"
      aria-label="Количество"
    />

    <button
      type="button"
      class="quantity-btn"
      :disabled="disabled || normalizedValue >= max"
      @click="increase"
      aria-label="Увеличить количество"
    >
      +
    </button>
  </div>
</template>
