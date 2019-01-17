export function confirmDeleteAction() {
  const deleteActions = document.querySelectorAll('.action-delete');
  for (let i = 0; i < deleteActions.length; i++) {
    const deleteAction = deleteActions[i];
    deleteAction.addEventListener('click', (e) => {
      const message = 'Ви дійсно хочете видалити даний запис?' + "\n" + 'Відновити вилучений запис буде неможливо';
      if (!confirm(message)) {
        e.preventDefault();
      }
    });
  }
}