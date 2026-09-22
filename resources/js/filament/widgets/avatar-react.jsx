import React, { useRef, useState } from 'react'
import { createRoot } from 'react-dom/client'
import Avatar from '../../../../vendor/filament/support/resources/js/react/Avatar'

function AvatarReact() {
    const [size, setSize] = useState('lg')
    const [circular, setCircular] = useState(true)
    const [accent, setAccent] = useState(false)
    const [loaded, setLoaded] = useState(false)
    const image = useRef(null)

    return (
        <div className="avatar-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <div className="avatar-demo-profile">
                <Avatar
                    ref={image}
                    src="/images/avatar-dan.png"
                    alt="Dan Harrin"
                    size={size}
                    circular={circular}
                    className={accent ? 'avatar-demo-accent' : undefined}
                    onLoad={() =>
                        setLoaded(image.current instanceof HTMLImageElement)
                    }
                />
                <div>
                    <strong>Dan Harrin</strong>
                    <p>Filament maintainer</p>
                </div>
            </div>
            <div className="avatar-demo-controls">
                <label>
                    Size{' '}
                    <select
                        value={size}
                        onChange={(event) => setSize(event.target.value)}
                    >
                        <option value="sm">Small</option>
                        <option value="md">Medium</option>
                        <option value="lg">Large</option>
                        <option value="avatar-demo-large">Custom</option>
                    </select>
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={circular}
                        onChange={(event) => setCircular(event.target.checked)}
                    />{' '}
                    Circular
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
            <button
                type="button"
                className="fi-btn fi-size-sm"
                onClick={() => {
                    setSize('md')
                    setCircular(true)
                    setAccent(false)
                }}
            >
                Reset to defaults
            </button>
            <output data-loaded={loaded}>
                {loaded ? 'Image loaded · image ref verified' : 'Loading image'}
            </output>
        </div>
    )
}

export default function mountAvatarReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<AvatarReact {...props} />)
    update(initialProps)

    return { update, destroy: () => root.unmount() }
}
