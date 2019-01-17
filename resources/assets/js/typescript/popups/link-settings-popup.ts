import {FormPopup} from "./form-popup";

export class LinkSettingsPopup extends FormPopup {

  protected fields: any[] = [
    { type: 'text', name: 'href', label: 'Адреса' },
    { type: 'text', name: 'title', label: 'Текст' },
    { type: 'text', name: 'value', label: 'Значення' },
    { type: 'button', label: 'Apply', onclick: () => this.hide(), ignore: true }
  ];

}