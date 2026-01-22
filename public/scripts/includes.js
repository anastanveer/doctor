const includeTargets = [...document.querySelectorAll("[data-include]")];

const includePromises = includeTargets.map((target) => {
  const file = target.getAttribute("data-include");
  if (!file) {
    return Promise.resolve();
  }

  return fetch(file)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Failed to load ${file}`);
      }
      return response.text();
    })
    .then((html) => {
      target.outerHTML = html;
    })
    .catch((error) => {
      console.warn(error);
    });
});

Promise.allSettled(includePromises).then(() => {
  const currentPage = document.body.getAttribute("data-page");
  if (!currentPage) {
    return;
  }

  const activeLink = document.querySelector(`[data-nav="${currentPage}"]`);
  if (activeLink) {
    activeLink.classList.add("is-active");
  }
});
