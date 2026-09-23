import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import Actions from '../../../../vendor/filament/support/resources/js/react/Actions'

function ActionsReact() {
    const [alignment, setAlignment] = useState('start')
    const [fullWidth, setFullWidth] = useState(false)
    const [visible, setVisible] = useState(true)
    const [name, setName] = useState('Community garden')
    const [message, setMessage] = useState('No changes saved.')
    return (
        <section className="actions-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <form
                onSubmit={(event) => {
                    event.preventDefault()
                    setMessage(`Saved ${name} locally.`)
                }}
            >
                <label>
                    Project name{' '}
                    <input
                        data-testid="name"
                        value={name}
                        onChange={(event) => setName(event.target.value)}
                    />
                </label>
                {visible && (
                    <Actions
                        alignment={alignment}
                        fullWidth={fullWidth}
                        data-testid="actions"
                    >
                        <button type="submit" data-testid="save">
                            Save
                        </button>
                        <button
                            type="button"
                            data-testid="archive"
                            onClick={() =>
                                setMessage(`Archived ${name} locally.`)
                            }
                        >
                            Archive
                        </button>
                        <a href="#react-project">View project</a>
                    </Actions>
                )}
            </form>
            <div className="icon-demo-controls">
                <label>
                    Alignment{' '}
                    <select
                        data-testid="alignment"
                        value={alignment}
                        onChange={(event) => setAlignment(event.target.value)}
                    >
                        {[
                            'start',
                            'left',
                            'center',
                            'end',
                            'right',
                            'between',
                            'justify',
                        ].map((value) => (
                            <option key={value}>{value}</option>
                        ))}
                    </select>
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="full-width"
                        checked={fullWidth}
                        onChange={(event) => setFullWidth(event.target.checked)}
                    />
                    Full width
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="visible"
                        checked={visible}
                        onChange={(event) => setVisible(event.target.checked)}
                    />
                    Show actions
                </label>
                <button
                    type="button"
                    data-testid="reset"
                    onClick={() => {
                        setAlignment('start')
                        setFullWidth(false)
                        setVisible(true)
                        setName('Community garden')
                        setMessage('No changes saved.')
                    }}
                >
                    Reset
                </button>
            </div>
            <p role="status" data-testid="result">
                {message}
            </p>
            <p id="react-project" tabIndex={-1}>
                Community garden · Project details
            </p>
        </section>
    )
}

export default function mountActionsReact({ host, props }) {
    const root = createRoot(host)
    root.render(<ActionsReact {...props} />)
    return {
        update: (next) => root.render(<ActionsReact {...next} />),
        destroy: () => root.unmount(),
    }
}
