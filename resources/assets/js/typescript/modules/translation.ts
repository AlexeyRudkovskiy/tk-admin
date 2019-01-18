export default class Translation {

  protected translation: any = null;

  private constructor() {}

  private static instance: Translation = null;

  public static getInstance() {
    if (this.instance === null) {
      this.instance = new Translation();
    }

    return this.instance;
  }

  public static setup() {
    const instance = Translation.getInstance();
    instance.load();
  }

  public static translateAutomatically() {
    const instance = Translation.getInstance();
    const elements = [...document.querySelectorAll('[data-translate]:not([data-translated])')];
    elements
      .forEach((element: HTMLElement) => {
        element.innerText = instance.translate(element.getAttribute('data-translate'));
        element.setAttribute('data-translated', 'data-translated');
      })
  }

  public load() {
    const xhr = new XMLHttpRequest();
    xhr.open('get', '/admin/api/translation', false);
    xhr.addEventListener('readystatechange', () => {
      if (xhr.readyState === 4 && xhr.status === 200) {
        this.translation = JSON.parse(xhr.responseText);
      }
    });
    xhr.send(null);
  }

  public translate(key: string): string {
    let _translation = this.translation;
    key.split('.')
      .forEach(item => _translation = _translation[item]);

    return _translation;
  }

}