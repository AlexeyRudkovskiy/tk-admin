import axios from 'axios';

export function toggleBoolean() {

  const elements = document.querySelectorAll('.toggle-using-ajax');
  for (let i = 0; i < elements.length; i++) {
    const element = elements[i] as HTMLLinkElement;
    element.addEventListener('click', (e) => {
      e.preventDefault();
      const url = element.getAttribute('href');
      let counter = 1;
      element.innerHTML = '';
      element.setAttribute('disabled', 'disabled');

      const interval = (window as any).setInterval(() => {
        element.innerText = '';
        for (let _i = 0; _i < counter; _i++) {
          element.innerText += '.';
        }
        counter++;
        if (counter > 4) {
          counter = 1;
        }
      }, 200);

      (window as any).axios(url)
        .then(response => response.data)
        .then(response => element.innerText = response as string)
        .then(() => (window as any).clearInterval(interval))
        .then(() => element.removeAttribute('disabled'));
    });
  }

}