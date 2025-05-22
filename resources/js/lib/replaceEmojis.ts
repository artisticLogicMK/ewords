function emojiToHex(emoji) {
  return [...emoji]
    .map(char => char.codePointAt(0).toString(16))
    .join('-')
}

async function isEmojiSupported(emoji) {
  const hex = emojiToHex(emoji)
  const url = `https://cdnjs.cloudflare.com/ajax/libs/twemoji/14.0.2/72x72/${hex}.png`

  try {
    const response = await fetch(url, { method: 'HEAD' })
    return response.ok // True if image exists, false if not
  } catch (error) {
    console.error('Error checking emoji:', error);
    return false
  }
}

async function replaceEmojisWithImages(text) {
  const emojis = text.match(/\p{Emoji_Presentation}|\p{Extended_Pictographic}/gu) || [];

  for (const emoji of emojis) {
    if (await isEmojiSupported(emoji)) {
      const hex = emojiToHex(emoji)
      const imgTag = `<img src="https://cdnjs.cloudflare.com/ajax/libs/twemoji/14.0.2/72x72/${hex}.png" style="height: 100%" />`
      text = text.replace(emoji, imgTag)
    }
  }

  return text
}

export default replaceEmojisWithImages