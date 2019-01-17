import {Field} from "../field";
import {Editor} from "./editor";

export class WYSIWYGField implements Field {

  private wysiwygEditors = [];

  private forms: HTMLFormElement[] = null;

  private editors: Editor[] = [];

  public constructor() {
    this.wysiwygEditors = document.getElementsByClassName('wysiwyg-container') as any;
    this.forms = [...document.querySelectorAll('form')];
  }

  render() {
    let i = 0;
    for (let editor of this.wysiwygEditors) {
      const id = 'editor:' + i + '@' + editor.getAttribute('data-name');
      this.initEditor(editor, id);
      i++;
    }

    for (let form of this.forms) {
      const editors = [...form.querySelectorAll('.wysiwyg-container')];
      if (editors.length > 0) {
        form.addEventListener('submit', () => {
          const ids = editors.map(editor => editor.getAttribute('data-id'));
          this.editors
            .filter(editor => ids.indexOf(editor.identifier) > -1)
            .forEach(editor => editor.saveContent());
        });
      }
    }
  }

  private initEditor(element: HTMLElement, id: string) {
    element.setAttribute('data-id', id);
    this.editors.push(new Editor(element, id));
  }

}