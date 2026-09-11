import { Link } from '@inertiajs/vue3'
import { h, ref } from 'vue'
import InertiaFeatures from './InertiaFeatures.vue'

export default {
    props: ['section', 'report', 'endpoints'],
    setup(props) {
        const notes = ref('Follow up with the customer success team.')

        return () => [
            h(
                'section',
                {
                    class: 'fi-inertia-workbench',
                    'aria-label': 'Inertia report',
                },
                [
                    h(
                        'p',
                        { class: 'fi-inertia-eyebrow' },
                        'Rendered by Vue · navigated by Livewire',
                    ),
                    h(
                        'nav',
                        { 'aria-label': 'Report sections' },
                        ['overview', 'activity'].map((section) =>
                            h(
                                Link,
                                {
                                    href: props.endpoints[section],
                                    preserveState: true,
                                    'aria-current':
                                        props.section === section
                                            ? 'page'
                                            : undefined,
                                },
                                () =>
                                    section === 'overview'
                                        ? 'Overview'
                                        : 'Activity',
                            ),
                        ),
                    ),
                    h('h2', { id: 'inertia-report-title' }, props.report.title),
                    h('p', props.report.description),
                    h(
                        'label',
                        { for: 'report-notes' },
                        'Working notes (Vue state)',
                    ),
                    h('textarea', {
                        id: 'report-notes',
                        value: notes.value,
                        onInput: (event) => {
                            notes.value = event.target.value
                        },
                    }),
                    h(
                        'p',
                        'Notes should survive a Livewire shell refresh.',
                    ),
                ],
            ),
            h(InertiaFeatures),
        ]
    },
}
