import {Popup, PopupSize} from "../popup";
import {SingletonPopup} from "./singleton-popup";
import {AsyncModulesContainer} from "../async-modules-container";

export class FilePopup extends SingletonPopup {

  private mediaContainer: HTMLDivElement = null;

  private mediaGrid: HTMLDivElement = null;

  private currentFolder: string = null;

  private xhr: XMLHttpRequest = null;

  private onFileSelectedCallback: any = null;

  private type: string = null;

  private isMultiple = true;

  private selected: any = [];

  constructor() {
    super(true);
    this.createMediaGrid();
    this.xhr = new XMLHttpRequest();
    this.size = PopupSize.FULLPAGE;

    this.popupContentContainer.classList.add('filebrowser');

    this.createDragAndDropPlaceholder('filebrowser');
    this.popupHeaderLabel.setAttribute('data-translate', 'dashboard.frontend.popup.file.header');
  }

  public get folder() {
    if (typeof this.currentFolder === "undefined" || this.currentFolder.length < 1) {
      return '';
    }
    return this.currentFolder;
  }

  public show() {
    this.popupContainer.classList.remove('hidden');
    AsyncModulesContainer.instance.runModules('files');
    if (this.popupContentContainer.classList.contains('popup-fullpage')) {
      document.body.style.overflow = 'hidden';
    }
  }

  hide(): void {
    super.hide();

    if (this.popupContentContainer.classList.contains('popup-fullpage')) {
      document.body.style.overflow = 'visible';
      document.body.style.overflowX = 'hidden';
    }
  }

  public clearContent() {
    while (this.mediaGrid.firstElementChild) {
      this.mediaGrid.removeChild(this.mediaGrid.firstElementChild);
    }
  }

  public selectFile(type: string, callback: any) {
    this.onFileSelectedCallback = callback;
    this.type = type;
    this.isMultiple = false;

    this.show();
    this.loadFilesAndFolders();
  }

  public selectFiles(type: string, callback: any) {
    this.selectFile(type, callback);
    this.isMultiple = true;
  }

  public addFolder(directory: any): HTMLDivElement {
    const folder = this.createGridItem();
    const icon = document.createElement('i');
    const text = document.createElement('span');

    folder.classList.add('media-grid-item-folder');

    icon.classList.add('fa','fa-folder', 'icon');
    text.classList.add('folder-text');
    text.innerHTML = directory.short;

    folder.appendChild(icon);
    folder.appendChild(text);

    this.mediaGrid.appendChild(folder);

    return folder;
  }

  public addFile(file: any): HTMLDivElement {
    const gridItem = this.createGridItem();
    const image = document.createElement('img');

    gridItem.classList.add('media-grid-item-file');
    image.src = file.thumbnail;
    image.addEventListener('load', () => {
      gridItem.style.height = gridItem.offsetWidth + 'px';
      image.style.height = gridItem.offsetWidth + 'px';
    });

    gridItem.appendChild(image);

    this.mediaGrid.appendChild(gridItem);

    return gridItem;
  }

  public getFilePath(file: any) {
    return file.path + file.name + '.' + file.extension;
  }

  public getThumbnailPath(file: any, thumbnail: string) {
    return file.path + file.name + '-' + thumbnail + '.' + file.extension;
  }

  public reload() {
    this.loadFilesAndFolders(this.currentFolder);
  }

  private loadFilesAndFolders(folder?: string) {
    if (this.xhr !== null) {
      this.xhr.abort()
    }

    this.xhr.open('GET', '/admin/media?folder=' + (folder ? folder : ''), true);
    this.xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    this.xhr.addEventListener('readystatechange', (e) => {
      if (this.xhr.readyState === 4 && this.xhr.status === 200) {
        const response = JSON.parse(this.xhr.responseText);
        this.processResponse(response);
        this.currentFolder = folder;
      }
    });
    this.xhr.send(null);
  }

  private createMediaGrid(): void {
    const mediaContainer = document.createElement('div');
    const mediaGrid = document.createElement('div');

    mediaContainer.classList.add('media-container');
    mediaGrid.classList.add('media-grid');
    mediaContainer.appendChild(mediaGrid);

    this.mediaContainer = mediaContainer;
    this.mediaGrid = mediaGrid;

    this.clearContent();
    this.content.appendChild(this.mediaContainer);

    if (this.isMultiple) {
      const mediaFooter = document.createElement('div');
      const button = document.createElement('a');

      mediaFooter.classList.add('media-footer');
      button.classList.add('btn', 'btn-primary');
      button.innerHTML = 'Обрати';

      button.addEventListener('click', () => {
        if (this.onFileSelectedCallback !== null) {
          this.onFileSelectedCallback.call(window, this.selected);
        }
        this.selected = [];
      });

      mediaFooter.appendChild(button);

      this.content.appendChild(mediaFooter);
    }
  }

  private createGridItem(): HTMLDivElement {
    const folder = document.createElement('div');
    folder.classList.add('media-grid-item');
    return folder;
  }

  private processResponse(data: any) {
    this.clearContent();

    if (typeof data.directories !== "undefined" && data.directories !== null) {
      for (let directory of data.directories) {
        const folder = this.addFolder(directory);
        folder.addEventListener('click', () => this.loadFilesAndFolders(directory.full));
      }
    }

    for (let file of data.files) {
      const fileElement = this.addFile(file);
      fileElement.addEventListener('click', () => {
        if (!this.isMultiple) {
          if (this.onFileSelectedCallback !== null) {
            this.onFileSelectedCallback.call(window, file);
          }
        } else {
          if (this.selected.indexOf(file) > -1) {
            this.selected.splice(this.selected.indexOf(file), 1);
          } else {
            this.selected.push(file);
          }
          fileElement.classList.toggle('selected');
        }
      });
    }

    // const firstImage = this.mediaGrid.firstElementChild as HTMLElement;
    // const width = firstImage.offsetWidth;
    //
    // for (let i in this.mediaGrid.childNodes) {
    //   const item = this.mediaGrid.childNodes[i] as any;
    //   item.style.width = width + 'px';
    //   item.style.height = width + 'px';
    // }
  }

  private createDragAndDropPlaceholder(id: string) {
    /*
    <div class="media-upload hidden">
        <i class="fa fa-upload media-upload-icon"></i>
        <span class="media-upload-description">Drop files to upload them</span>
    </div>
     */

    const placeholderContainer = document.createElement('div');
    const icon = document.createElement('i');
    const description = document.createElement('span');

    placeholderContainer.classList.add('media-upload', 'hidden');

    icon.classList.add('fa', 'fa-upload', 'media-upload-icon');
    description.classList.add('media-upload-description');

    description.innerHTML = 'Drop files to upload them';

    placeholderContainer.appendChild(icon);
    placeholderContainer.appendChild(description);

    this.popupContentContainer.appendChild(placeholderContainer);
  }

}