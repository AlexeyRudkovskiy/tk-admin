export class Gallery {

  private sortable: any = null;

  public constructor(private gallery: HTMLDivElement) {
    this.sortable = (window as any).Sortable.create(this.gallery, {
      animation: 150,
      group: 'editor'
    });

    const items = gallery.querySelectorAll('div > img');
    for (let i = 0; i < items.length; i++) {
      const item = items[i] as any;
      if (item.offsetHeight < 1) {
        item.addEventListener('load', () => {
          item.style.height = item.offsetWidth + 'px';
        });
      } else {
        item.style.height = item.offsetWidth + 'px';
      }
    }
  }

}