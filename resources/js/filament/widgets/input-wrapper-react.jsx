import React, { useRef, useState } from 'react'
import { createRoot } from 'react-dom/client'
import { PoundSterling } from 'lucide-react'
import InputWrapper from '../../../../vendor/filament/support/resources/js/react/InputWrapper'
import Icon from '../../../../vendor/filament/support/resources/js/react/Icon'

function InputWrapperReact() {
    const [amount, setAmount] = useState('125')
    const [disabled, setDisabled] = useState(false)
    const [invalid, setInvalid] = useState(false)
    const [affixes, setAffixes] = useState(true)
    const [icons, setIcons] = useState(false)
    const [inline, setInline] = useState(false)
    const [actions, setActions] = useState(false)
    const input = useRef(null)
    return (
        <div className="icon-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <label htmlFor="react-price">Workshop price</label>
            <InputWrapper
                disabled={disabled}
                valid={!invalid}
                prefix={affixes ? '£' : null}
                suffix={affixes ? 'GBP' : null}
                inlinePrefix={inline}
                inlineSuffix={inline}
                prefixIcon={
                    icons ? (
                        <Icon aria-hidden="true">
                            <PoundSterling />
                        </Icon>
                    ) : null
                }
                suffixActions={
                    actions ? (
                        <button
                            type="button"
                            disabled={disabled}
                            onClick={() => setAmount('')}
                        >
                            Clear
                        </button>
                    ) : null
                }
            >
                <input
                    ref={input}
                    id="react-price"
                    className="fi-input"
                    type="number"
                    min="1"
                    required
                    value={amount}
                    onChange={(event) => setAmount(event.target.value)}
                    disabled={disabled}
                    aria-invalid={invalid}
                    aria-describedby={invalid ? 'react-price-error' : undefined}
                />
            </InputWrapper>
            {invalid && (
                <p id="react-price-error" role="status">
                    Enter a workshop price of at least £1.
                </p>
            )}
            <div className="icon-demo-controls">
                {[
                    [disabled, setDisabled, 'Disabled'],
                    [invalid, setInvalid, 'Invalid'],
                    [affixes, setAffixes, 'Text affixes'],
                    [icons, setIcons, 'Icon'],
                    [inline, setInline, 'Inline'],
                    [actions, setActions, 'Clear action'],
                ].map(([checked, update, label]) => (
                    <label key={label}>
                        <input
                            type="checkbox"
                            checked={checked}
                            onChange={(event) => update(event.target.checked)}
                        />{' '}
                        {label}
                    </label>
                ))}
            </div>
            <div className="icon-demo-controls">
                <button
                    type="button"
                    className="fi-btn fi-size-sm"
                    onClick={() => setInvalid(!input.current.checkValidity())}
                >
                    Validate
                </button>
                <button
                    type="button"
                    className="fi-btn fi-size-sm"
                    onClick={() => {
                        setAmount('125')
                        setDisabled(false)
                        setInvalid(false)
                        setAffixes(true)
                        setIcons(false)
                        setInline(false)
                        setActions(false)
                    }}
                >
                    Reset
                </button>
            </div>
            <p>
                Native input value: {amount || 'empty'}. Wrapper styling and
                input semantics are supplied separately.
            </p>
        </div>
    )
}

export default function mountInputWrapperReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<InputWrapperReact {...props} />)
    update(initialProps)
    return { update, destroy: () => root.unmount() }
}
