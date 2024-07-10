const copys = (textToCopy, alertText, link) => {
  const textarea = document.createElement("textarea");
  textarea.value = textToCopy;
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand("copy");
  document.body.removeChild(textarea);
  alert(alertText);
  // 跳转到其他页面
  if (link) {
    // 在新窗口打开页面
    window.open(link, "_blank");
  }
};
