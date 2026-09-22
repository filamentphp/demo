import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import Checkbox from '../../../../vendor/filament/support/resources/js/react/Checkbox'

function CheckboxReact() {
    const [checked, setChecked] = useState(true)
    const [disabled, setDisabled] = useState(false)
    const [invalid, setInvalid] = useState(false)
    const [required, setRequired] = useState(false)
    const [submitted, setSubmitted] = useState('Not submitted')
    return (
        <div className="checkbox-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <form
                onSubmit={(event) => {
                    event.preventDefault()
                    setSubmitted(
                        JSON.stringify([...new FormData(event.currentTarget)]),
                    )
                }}
            >
                <label>
                    <Checkbox
                        name="digest"
                        value="weekly"
                        checked={checked}
                        onChange={(event) => setChecked(event.target.checked)}
                        disabled={disabled}
                        valid={!invalid}
                        required={required}
                        aria-invalid={invalid}
                    />{' '}
                    Send me the weekly digest
                </label>
                <button type="submit" className="fi-btn fi-size-sm">
                    Preview submission
                </button>
            </form>
            <div className="checkbox-demo-controls">
                <label>
                    <input
                        type="checkbox"
                        checked={disabled}
                        onChange={(event) => setDisabled(event.target.checked)}
                    />{' '}
                    Disabled
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={invalid}
                        onChange={(event) => setInvalid(event.target.checked)}
                    />{' '}
                    Invalid
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={required}
                        onChange={(event) => setRequired(event.target.checked)}
                    />{' '}
                    Required
                </label>
            </div>
            <output>
                {checked ? 'Subscribed' : 'Not subscribed'} · {submitted}
            </output>
            <button
                type="button"
                className="fi-btn fi-size-sm"
                onClick={() => {
                    setChecked(true)
                    setDisabled(false)
                    setInvalid(false)
                    setRequired(false)
                    setSubmitted('Not submitted')
                }}
            >
                Reset
            </button>
        </div>
    )
}

export default function mountCheckboxReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<CheckboxReact {...props} />)
    update(initialProps)

    return { update, destroy: () => root.unmount() }
}
