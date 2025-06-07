const urlSlug = (value: string) => {
  return value.replace(/ /g, '_') // replace spaces first
  .replace(/[^a-zA-Z0-9_-]/g, '')  // remove anything not URL-safe
  .replace(/_+/g, "_")
  .toLowerCase()
}

export default urlSlug