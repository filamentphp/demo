<template>
    <svg :viewBox="vbox" :width="width" :height="height">
      <g>
        <path :stroke="stroke" :d="d" :id="textId" fill="none" />
        <text>
          <textPath :xlink:href="xlink" :fill="color" :startOffset="offset" text-anchor="middle">
            <slot />
          </textPath>
        </text>
      </g>
    </svg>
  </template>

  <script>
  import { defineComponent, ref, computed, onMounted, isVNode } from 'vue';

  export default defineComponent({
    props: {
      width: {
        type: Number,
        default: 200,
      },
      height: {
        type: Number,
        default: 65,
      },
      r: {
        type: Number,
        default: 25,
      },
      offset: {
        type: String,
        default: '50%',
      },
      textid: {
        type: String,
        default: '',
      },
      color: {
        type: String,
        default: 'currentColor',
      },
      debug: {
        type: Boolean,
        default: false,
      },
    },

    setup(props, { slots }) {
      const stroke = computed(() => (props.debug ? 'gray' : 'none'));
      const textId = computed(() => (props.textid === '' ? `vue-curve-text-${props._uid}` : props.textid));
      const xlink = computed(() => `#${textId.value}`);
      const vbox = computed(() => [0, 0, props.width, props.height].join(' '));
      const d = computed(() => `M 0,${props.height} Q ${props.width / 2},${props.height - props.r * 2} ${props.width},${props.height}`);

      onMounted(() => {
        const defaultSlot = slots.default;
        if (!defaultSlot) {
          console.error('Slot content is not defined');
          return;
        }
        if (defaultSlot.length > 1) {
          console.error('Slot content must be a textNode', defaultSlot);
          return;
        }
        if (!isVNode(defaultSlot[0]) || defaultSlot[0].type !== 4) {
          //console.error('Slot content must be a textNode', defaultSlot);
          return;
        }
      });

      return {
        stroke,
        textId,
        xlink,
        vbox,
        d,
      };
    },
  });
  </script>
