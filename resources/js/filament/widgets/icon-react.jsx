import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import { Check, X } from 'lucide-react'
import Icon from '../../../../vendor/filament/support/resources/js/react/Icon'

function IconReact() {
    const [size, setSize] = useState('md')
    const [saved, setSaved] = useState(true)
    const [image, setImage] = useState(false)
    const [named, setNamed] = useState(true)
    const [accent, setAccent] = useState(false)
    const label = saved ? 'Saved' : 'Not saved'
    const Artwork = saved ? Check : X
    return (
        <div className="icon-demo" data-framework="react">
            <h2 className="fi-section-header-heading">
                React · JavaScript + Lucide
            </h2>
            <div className="icon-demo-preview" data-testid="icon-preview">
                <Icon
                    size={size}
                    src={
                        image
                            ? `/images/icon-${saved ? 'check' : 'x'}.svg`
                            : undefined
                    }
                    alt={named ? label : ''}
                    role={!image && named ? 'img' : undefined}
                    aria-label={!image && named ? label : undefined}
                    aria-hidden={named ? undefined : true}
                    className={accent ? 'icon-demo-accent' : undefined}
                    data-state={saved ? 'saved' : 'unsaved'}
                >
                    <Artwork aria-hidden="true" />
                </Icon>
                <span>
                    {named ? 'Named' : 'Decorative'} {image ? 'image' : 'SVG'} ·{' '}
                    {label}
                </span>
            </div>
            <div className="icon-demo-preview">
                {['xs', 'sm', 'md', 'lg', 'xl', '2xl'].map((size) => (
                    <span className="icon-demo-sample" key={size}>
                        <Icon size={size} aria-hidden="true">
                            <Check />
                        </Icon>
                        <span>{size}</span>
                    </span>
                ))}
            </div>
            <div className="icon-demo-controls">
                <label>
                    Size{' '}
                    <select
                        value={size}
                        onChange={(event) => setSize(event.target.value)}
                    >
                        {['xs', 'sm', 'md', 'lg', 'xl', '2xl'].map((size) => (
                            <option key={size}>{size}</option>
                        ))}
                    </select>
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={image}
                        onChange={(event) => setImage(event.target.checked)}
                    />{' '}
                    Image
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={named}
                        onChange={(event) => setNamed(event.target.checked)}
                    />{' '}
                    Accessible name
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={accent}
                        onChange={(event) => setAccent(event.target.checked)}
                    />{' '}
                    Theme accent
                </label>
            </div>
            <div className="icon-demo-controls">
                <button
                    type="button"
                    className="fi-btn fi-size-sm"
                    onClick={() => setSaved(!saved)}
                >
                    Toggle artwork
                </button>
                <button
                    type="button"
                    className="fi-btn fi-size-sm"
                    onClick={() => {
                        setSize('md')
                        setSaved(true)
                        setImage(false)
                        setNamed(true)
                        setAccent(false)
                    }}
                >
                    Reset
                </button>
            </div>
        </div>
    )
}

export default function mountIconReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<IconReact {...props} />)
    update(initialProps)
    return { update, destroy: () => root.unmount() }
}
