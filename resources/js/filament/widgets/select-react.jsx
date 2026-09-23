import React, { useRef, useState } from 'react'
import { createRoot } from 'react-dom/client'
import Select from '../../../../vendor/filament/support/resources/js/react/Select'
import InputWrapper from '../../../../vendor/filament/support/resources/js/react/InputWrapper'

function SelectReact() {
    const [workshop, setWorkshop] = useState('drawing')
    const [extras, setExtras] = useState(['ceramics'])
    const [disabled, setDisabled] = useState(false)
    const [inline, setInline] = useState(false)
    const [remove, setRemove] = useState(false)
    const [result, setResult] = useState('No submission yet')
    const form = useRef(null)
    const options = [
        ['drawing', 'Botanical drawing'],
        ['ceramics', 'Studio ceramics'],
        ['printing', 'Printmaking'],
        ['archived', 'Watercolours (unavailable)'],
    ].filter(([value]) => !remove || value !== 'drawing')
    return (
        <div className="radio-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <form
                ref={form}
                onSubmit={(event) => {
                    event.preventDefault()
                    setResult(
                        JSON.stringify([...new FormData(event.currentTarget)]),
                    )
                }}
                onReset={(event) => {
                    event.preventDefault()
                    setWorkshop('drawing')
                    setExtras(['ceramics'])
                    event.currentTarget.elements.delivery.value = 'email'
                    setResult('Reset to defaults')
                }}
            >
                <label htmlFor="react-workshop">Main workshop</label>
                <InputWrapper
                    prefix="Art"
                    inlinePrefix={inline}
                    disabled={disabled}
                >
                    <Select
                        id="react-workshop"
                        data-testid="workshop"
                        name="workshop"
                        required
                        value={workshop}
                        onChange={(event) => setWorkshop(event.target.value)}
                        disabled={disabled}
                        inlinePrefix={inline}
                    >
                        <option value="">Choose a workshop</option>
                        {options.map(([value, label]) => (
                            <option
                                key={value}
                                value={value}
                                disabled={value === 'archived'}
                            >
                                {label}
                            </option>
                        ))}
                    </Select>
                </InputWrapper>
                <label htmlFor="react-extras">
                    Additional workshops (multiple)
                </label>
                <InputWrapper disabled={disabled}>
                    <Select
                        id="react-extras"
                        data-testid="extras"
                        name="extras"
                        multiple
                        size={4}
                        value={extras}
                        disabled={disabled}
                        onChange={(event) =>
                            setExtras(
                                [...event.target.selectedOptions].map(
                                    (option) => option.value,
                                ),
                            )
                        }
                    >
                        {options.map(([value, label]) => (
                            <option
                                key={value}
                                value={value}
                                disabled={value === 'archived'}
                            >
                                {label}
                            </option>
                        ))}
                    </Select>
                </InputWrapper>
                <label htmlFor="react-delivery">
                    Ticket delivery (uncontrolled)
                </label>
                <InputWrapper>
                    <Select
                        id="react-delivery"
                        name="delivery"
                        data-testid="delivery"
                        defaultValue="email"
                    >
                        <option value="email">Email tickets</option>
                        <option value="desk">Collect at reception</option>
                    </Select>
                </InputWrapper>
                <div className="radio-demo-controls">
                    <button type="submit" data-testid="submit">
                        Submit locally
                    </button>
                    <button type="reset" data-testid="reset">
                        Reset form
                    </button>
                </div>
            </form>
            <div className="radio-demo-controls">
                <label>
                    <input
                        type="checkbox"
                        data-testid="disabled"
                        checked={disabled}
                        onChange={(event) => setDisabled(event.target.checked)}
                    />{' '}
                    Disable workshops
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="inline"
                        checked={inline}
                        onChange={(event) => setInline(event.target.checked)}
                    />{' '}
                    Inline prefix
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="remove"
                        checked={remove}
                        onChange={(event) => setRemove(event.target.checked)}
                    />{' '}
                    Remove drawing option
                </label>
                <button
                    type="button"
                    data-testid="empty"
                    onClick={() => {
                        setWorkshop('')
                        setExtras([])
                    }}
                >
                    Clear selections
                </button>
                <button
                    type="button"
                    data-testid="archived"
                    onClick={() => {
                        setWorkshop('archived')
                        setExtras(['printing', 'archived'])
                    }}
                >
                    Select unavailable option
                </button>
                <button
                    type="button"
                    data-testid="validate"
                    onClick={() =>
                        setResult(
                            form.current.reportValidity() ? 'Valid' : 'Invalid',
                        )
                    }
                >
                    Validate
                </button>
            </div>
            <p data-testid="state">
                Selection: {JSON.stringify(workshop)}; extras:{' '}
                {JSON.stringify(extras)}
            </p>
            <output data-testid="result">{result}</output>
            <p>
                Controlled reset is host-owned. Removing a selected option
                preserves state but React displays the first enabled option.
                Submissions stay in this browser.
            </p>
        </div>
    )
}

export default function mountSelectReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<SelectReact {...props} />)
    update(initialProps)
    return { update, destroy: () => root.unmount() }
}
