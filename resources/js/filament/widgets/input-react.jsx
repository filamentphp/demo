import React, { useRef, useState } from 'react'
import { createRoot } from 'react-dom/client'
import Input from '../../../../vendor/filament/support/resources/js/react/Input'
import InputWrapper from '../../../../vendor/filament/support/resources/js/react/InputWrapper'

function InputReact() {
    const [price, setPrice] = useState('0')
    const [disabled, setDisabled] = useState(false)
    const [readOnly, setReadOnly] = useState(false)
    const [inline, setInline] = useState(false)
    const [result, setResult] = useState('No submission yet')
    const form = useRef(null)
    return (
        <div className="radio-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <form
                ref={form}
                onSubmit={(event) => {
                    event.preventDefault()
                    setResult(
                        JSON.stringify(
                            Object.fromEntries(
                                new FormData(event.currentTarget),
                            ),
                        ),
                    )
                }}
                onReset={(event) => {
                    event.preventDefault()
                    setPrice('0')
                    event.currentTarget.elements.title.value =
                        event.currentTarget.elements.title.defaultValue
                    event.currentTarget.elements.email.value =
                        event.currentTarget.elements.email.defaultValue
                    setResult('Reset to defaults')
                }}
            >
                <label htmlFor="react-title">
                    Workshop title (uncontrolled)
                </label>
                <InputWrapper>
                    <Input
                        id="react-title"
                        name="title"
                        defaultValue="Botanical drawing"
                        required
                    />
                </InputWrapper>
                <label htmlFor="react-email">
                    Contact email (uncontrolled)
                </label>
                <InputWrapper>
                    <Input
                        id="react-email"
                        name="email"
                        type="email"
                        defaultValue="ada@example.com"
                        required
                    />
                </InputWrapper>
                <label htmlFor="react-price">Ticket price (controlled)</label>
                <InputWrapper
                    prefix="£"
                    suffix="GBP"
                    inlinePrefix={inline}
                    inlineSuffix={inline}
                    disabled={disabled}
                >
                    <Input
                        id="react-price"
                        name="price"
                        type="number"
                        min="0"
                        step="0.5"
                        required
                        value={price}
                        onChange={(event) =>
                            setPrice(event.currentTarget.value)
                        }
                        disabled={disabled}
                        readOnly={readOnly}
                        inlinePrefix={inline}
                        inlineSuffix={inline}
                    />
                </InputWrapper>
                <div className="radio-demo-controls">
                    <button type="submit">Submit locally</button>
                    <button type="reset">Reset form</button>
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
                    Disabled price
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="readonly"
                        checked={readOnly}
                        onChange={(event) => setReadOnly(event.target.checked)}
                    />{' '}
                    Read-only price
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="inline"
                        checked={inline}
                        onChange={(event) => setInline(event.target.checked)}
                    />{' '}
                    Inline affixes
                </label>
                <button
                    type="button"
                    data-testid="empty"
                    onClick={() => setPrice('')}
                >
                    Empty price
                </button>
                <button
                    type="button"
                    data-testid="zero"
                    onClick={() => setPrice('0')}
                >
                    Zero price
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
                Host price: {JSON.stringify(price)} ({typeof price})
            </p>
            <output data-testid="result">{result}</output>
            <p>
                Reset is host-owned for controlled state. Disabled fields are
                omitted from submission; read-only fields remain included.
            </p>
        </div>
    )
}

export default function mountInputReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<InputReact {...props} />)
    update(initialProps)
    return { update, destroy: () => root.unmount() }
}
