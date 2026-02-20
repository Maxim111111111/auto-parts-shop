<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
  },
  size: {
    type: String,
    default: 'md',
  },
  block: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'button',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  to: {
    type: [String, Object],
    default: null,
  },
  href: {
    type: String,
    default: '',
  },
  target: {
    type: String,
    default: '_self',
  },
});

const emit = defineEmits(['click']);

const tag = computed(() => {
  if (props.to) return RouterLink;
  if (props.href) return 'a';
  return 'button';
});

const classes = computed(() => [
  'btn',
  `btn-${props.variant}`,
  `btn-${props.size}`,
  { 'btn-block': props.block, 'is-disabled': props.disabled },
]);

const bind = computed(() => {
  if (props.to) {
    return {
      to: props.to,
      class: classes.value,
      'aria-disabled': props.disabled ? 'true' : null,
      tabindex: props.disabled ? -1 : null,
    };
  }

  if (props.href) {
    return {
      href: props.href,
      target: props.target,
      rel: props.target === '_blank' ? 'noreferrer noopener' : null,
      class: classes.value,
      'aria-disabled': props.disabled ? 'true' : null,
      tabindex: props.disabled ? -1 : null,
    };
  }

  return {
    type: props.type,
    disabled: props.disabled,
    class: classes.value,
  };
});

const handleClick = (event) => {
  if (props.disabled) {
    event.preventDefault();
    event.stopPropagation();
    return;
  }

  emit('click', event);
};
</script>

<template>
  <component :is="tag" v-bind="bind" @click="handleClick">
    <slot />
  </component>
</template>
