export function capitalize(string) {
    return string ? string.charAt(0).toUpperCase() + string.slice(1) : null;
}

export function redirect(path) {
    console.log(path)
    window.location.href = path;
}