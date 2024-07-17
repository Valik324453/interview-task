export function isValidUrl(url) {
  try {
    // Проверяем наличие схемы (http:// или https://)
    if (!/^https?:\/\//i.test(url)) {
      url = 'http://' + url; // добавляем http:// по умолчанию
    }

    const parsedUrl = new URL(url);

    // Проверяем, что хост содержит хотя бы одну точку (напр. google.com)
    if (!parsedUrl.hostname.includes('.')) {
      return false;
    }

    return true;
  } catch (e) {
    return false;
  }
}