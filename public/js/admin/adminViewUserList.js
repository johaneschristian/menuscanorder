function toggleEditStatusModal(userId) {
  const editedUserIdSpan = document.querySelector(`#edited-user-id`);
  const editedUserEmailSpan = document.querySelector(`#edited-user-email`);
  const editedUserStatus = document.querySelector(`#account-status`);

  editedUserIdSpan.setAttribute('value', userId);
  editedUserEmailSpan.setAttribute('value', getUserEmail(userId));
  editedUserStatus.value = getUserStatus(userId);
}

function getUserEmail(userId) {
  return document.querySelector(`#user-${userId}-email`).innerText;
}

function getUserName(userId) {
  return document.querySelector(`#user-${userId}-name`).innerText;
}

function getUserStatus(userId) {
  return document.querySelector(`#user-${userId}-status`).innerText;
}
