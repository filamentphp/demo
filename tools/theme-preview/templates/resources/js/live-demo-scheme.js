export const resolveScheme = (scheme, prefersDark) => {
    if (scheme === 'system') {
        return prefersDark ? 'dark' : 'light'
    }

    return scheme
}
