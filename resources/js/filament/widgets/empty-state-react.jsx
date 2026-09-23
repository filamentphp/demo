import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import { FolderPlus, Search } from 'lucide-react'
import EmptyState from '../../../../vendor/filament/support/resources/js/react/EmptyState'

function EmptyStateReact() {
    const [filtered, setFiltered] = useState(false)
    const [minimal, setMinimal] = useState(false)
    const [created, setCreated] = useState(0)
    const [message, setMessage] = useState('Your workspace is ready.')
    return (
        <section className="radio-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <EmptyState
                data-testid="empty-state"
                title={filtered ? 'Filtered projects' : 'All projects'}
                heading={filtered ? 'No matching projects' : 'No projects yet'}
                headingTag="h3"
                description={
                    minimal ? (
                        '  '
                    ) : (
                        <>
                            Make room for your next <strong>great idea</strong>.
                        </>
                    )
                }
                icon={
                    minimal ? null : filtered ? (
                        <Search aria-hidden="true" />
                    ) : (
                        <FolderPlus aria-hidden="true" />
                    )
                }
                iconColor={filtered ? 'gray' : 'primary'}
                iconSize={filtered ? 'sm' : 'lg'}
                compact={filtered}
                contained={!filtered}
            >
                {!minimal && (
                    <div className="radio-demo-controls">
                        <button
                            type="button"
                            data-testid="create"
                            onClick={() => {
                                setCreated(created + 1)
                                setMessage('Project draft created locally.')
                            }}
                        >
                            Create project
                        </button>
                        <a href="#react-projects">View workspace</a>
                    </div>
                )}
            </EmptyState>
            <div className="radio-demo-controls">
                <button
                    type="button"
                    data-testid="filter"
                    onClick={() => setFiltered(!filtered)}
                >
                    Toggle filtered view
                </button>
                <button
                    type="button"
                    data-testid="minimal"
                    onClick={() => setMinimal(!minimal)}
                >
                    Toggle details
                </button>
                <button
                    type="button"
                    data-testid="reset"
                    onClick={() => {
                        setFiltered(false)
                        setMinimal(false)
                        setCreated(0)
                        setMessage('Your workspace is ready.')
                    }}
                >
                    Reset
                </button>
            </div>
            <p role="status" data-testid="result">
                {message} Drafts: {created}
            </p>
            <div id="react-projects" tabIndex={-1}>
                Workspace ·{' '}
                {created
                    ? `${created} project draft${created === 1 ? '' : 's'}`
                    : 'No saved projects'}
            </div>
        </section>
    )
}

export default function mountEmptyStateReact({ host, props }) {
    const root = createRoot(host)
    root.render(<EmptyStateReact {...props} />)
    return {
        update: (next) => root.render(<EmptyStateReact {...next} />),
        destroy: () => root.unmount(),
    }
}
