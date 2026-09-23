import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import { Star } from 'lucide-react'
import Badge from '../../../../vendor/filament/support/resources/js/react/Badge'

function BadgeReact() {
    const [loading, setLoading] = useState(false)
    const [disabled, setDisabled] = useState(false)
    const [enhanced, setEnhanced] = useState(true)
    const [visible, setVisible] = useState(true)
    const [deleting, setDeleting] = useState(false)
    const [removed, setRemoved] = useState(false)
    const [count, setCount] = useState(0)
    return (
        <div className="callout-demo" data-framework="react">
            <h2 className="fi-section-header-heading">
                React · Project labels
            </h2>
            <p>
                Local-only project filters. Alt+R saves; Finish completes
                pending work.
            </p>
            <div className="radio-demo-controls" data-testid="palette">
                <Badge>New</Badge>
                <Badge color="gray" size="xs">
                    Archived
                </Badge>
                <Badge color="brand" size="sm" icon={<Star />}>
                    Priority
                </Badge>
                <Badge color="success" iconPosition="after" icon={<Star />}>
                    Approved
                </Badge>
            </div>
            {visible && (
                <div
                    className="radio-demo-controls"
                    data-testid="interactive-badges"
                >
                    <Badge
                        tag="a"
                        href="#react-projects"
                        data-testid="project-link"
                        disabled={disabled}
                        tooltip={
                            enhanced ? 'Jump to the project summary' : undefined
                        }
                    >
                        View projects
                    </Badge>
                    <Badge
                        tag="button"
                        type="submit"
                        form="react-badge-form"
                        data-testid="save-badge"
                        color="brand"
                        disabled={disabled}
                        loading={loading}
                        tooltip={
                            enhanced
                                ? 'Save project filters (Alt+R)'
                                : undefined
                        }
                        keyBindings={enhanced ? ['alt+r'] : undefined}
                        icon={<Star />}
                    >
                        {loading ? 'Saving filters' : 'Save filters'}
                    </Badge>
                    {!removed && (
                        <Badge
                            data-testid="delete-badge"
                            color="warning"
                            disabled={disabled}
                            onDelete={() => setDeleting(true)}
                            deleteLoading={deleting}
                            deleteLabel="Remove priority filter"
                        >
                            Priority filter
                        </Badge>
                    )}
                </div>
            )}
            <form
                id="react-badge-form"
                onSubmit={(event) => {
                    event.preventDefault()
                    setLoading(true)
                    setCount(count + 1)
                }}
            >
                <label>
                    Filter name{' '}
                    <input
                        data-testid="filter-name"
                        defaultValue="Community garden"
                    />
                </label>
            </form>
            <div className="icon-demo-controls">
                <button
                    type="button"
                    data-testid="finish"
                    onClick={() => {
                        setLoading(false)
                        if (deleting) {
                            setRemoved(true)
                            setDeleting(false)
                        }
                    }}
                >
                    Finish
                </button>
                <button
                    type="button"
                    data-testid="reset"
                    onClick={() => {
                        setLoading(false)
                        setDeleting(false)
                        setRemoved(false)
                        setCount(0)
                        setDisabled(false)
                        setEnhanced(true)
                        setVisible(true)
                    }}
                >
                    Reset
                </button>
            </div>
            <label>
                <input
                    type="checkbox"
                    data-testid="disabled"
                    checked={disabled}
                    onChange={(event) => setDisabled(event.target.checked)}
                />{' '}
                Disable actions
            </label>
            <label>
                <input
                    type="checkbox"
                    data-testid="enhanced"
                    checked={enhanced}
                    onChange={(event) => setEnhanced(event.target.checked)}
                />{' '}
                Tooltips and shortcut
            </label>
            <label>
                <input
                    type="checkbox"
                    data-testid="visible"
                    checked={visible}
                    onChange={(event) => setVisible(event.target.checked)}
                />{' '}
                Mount interactive badges
            </label>
            <p id="react-projects">Community garden · 12 projects</p>
            <p role="status" data-testid="result">
                {count} saves requested.{' '}
                {removed
                    ? 'Priority filter removed.'
                    : deleting
                      ? 'Removing priority filter…'
                      : loading
                        ? 'Saving filters…'
                        : 'Ready.'}
            </p>
        </div>
    )
}
export default function mountBadgeReact({ host, props }) {
    const root = createRoot(host)
    root.render(<BadgeReact {...props} />)
    return {
        update: (next) => root.render(<BadgeReact {...next} />),
        destroy: () => root.unmount(),
    }
}
