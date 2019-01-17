import {SingletonPopup} from "./singleton-popup";
import {Popup} from "../popup";

export abstract class FormPopup extends SingletonPopup {

  protected formContainer: HTMLFormElement = null;

  protected fields: any[] = [  ];

  protected onDataChanged: any = null;

  public constructor() {
    super(true);
  }

  public showForResult(callback: any) {
    this.show();
    this.onDataChanged = callback;
  }

  hide(): void {
    super.hide();
    if (this.onDataChanged !== null) {
      this.onDataChanged.call(window, this.data);
    }
  }

  public preparePopup() {
    this.clearContent();

    this.formContainer = document.createElement('form');
    this.formContainer.classList.add('form');

    this.popupContent.appendChild(this.formContainer);

    this.fields
      .forEach(item => this.createItem(item));
  }

  public set data(data: any) {
    for (let key in data) {
      const value = data[key];
      const field = this.fields
        .filter(item => item.name === key)
        .forEach(item => this.setValue(item, value));
    }
  }

  public get data(): any {
    return this.fields
      .filter(item => typeof item.ignore === "undefined" || !item.ignore)
      .map(item => ({ name: item.name, value: item.metadata.value }))
      .reduce((items, item) => ({ ...items, [item.name]: item.value }), {});
  }

  protected createItem(item) {
    const { name, label, type } = item;
    const id = (Math as any).random().toString(36).substr(2, 6);
    const createdField = this.createField(id, name, label);
    const { formField } = createdField;

    let appendFormFieldAutomaticaly = type !== 'button';

    if (appendFormFieldAutomaticaly) {
      this.formContainer.appendChild(formField);
    }

    switch (type) {
      case 'text':
        const createdField = this.createTextField(formField, name, id);
        item.element = createdField.inputElement;
        item.metadata = createdField.metadata || {};
        break;
      case 'button':
        const createdButton = this.createButtonField(item, name);
        item.element = createdButton.element;
        item.metadata = createdButton.metadata || {};
        break;
    }

    if (!appendFormFieldAutomaticaly && item.element !== null) {
      this.formContainer.appendChild(item.element);
    }
  }

  protected setValue(item: any, value: any) {
    switch (item.type) {
      case 'text':
        this.setTextFieldValue(item, value);
        break;
    }
  }

  protected createField(id: string, name: string, labelText: string) {
    const formField = document.createElement('div');
    const label = document.createElement('label');

    label.innerHTML = labelText;
    label.classList.add('form-control-label');
    label.setAttribute('for', id);

    formField.classList.add('form-group');
    formField.appendChild(label);

    return { formField, label };
  }

  protected createTextField(formField, name, id) {
    const inputElement = document.createElement('input');
    const metadata: any = {};
    inputElement.type = 'text';
    inputElement.name = name;
    inputElement.id = id;
    inputElement.classList.add('form-control');

    formField.appendChild(inputElement);
    inputElement.addEventListener('input', () => metadata.value = inputElement.value);

    return { inputElement, metadata };
  }

  protected createButtonField(formField, name) {
    const layout = document.createElement('div');
    const row = document.createElement('div');
    const button = document.createElement('input');

    layout.classList.add('form-group');
    row.classList.add('d-flex', 'flex-row-reverse');
    button.classList.add('btn', 'btn-primary');

    button.type = 'button';
    button.value = formField.label;

    layout.appendChild(row);
    row.appendChild(button);

    if (typeof formField.onclick !== "undefined" && formField.onclick !== null) {
      button.addEventListener('click', () => formField.onclick());
    }

    return {
      element: layout,
      metadata: {}
    };
  }

  protected setTextFieldValue(item: any, value: any) {
    if (typeof value === "undefined" && value === null) {
      return;
    }
    item.element.value = value;
    item.metadata.value = value;
  }

}
