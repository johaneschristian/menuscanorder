function displayAffiliatedBusinessForm() {
  const affiliatedBusinessForm = document.querySelector(`#affiliated-business-form`);
  const removeAffiliatedBusinessButton = document.querySelector(`#remove-business-button`);
  const addAffiliatedBusinessButton = document.querySelector(`#add-business-button`);

  toggleElement(affiliatedBusinessForm, true);
  toggleElement(removeAffiliatedBusinessButton, true);
  toggleElement(addAffiliatedBusinessButton, false);
}

function hideAffiliatedBusinessForm() {
  const affiliatedBusinessForm = document.querySelector(`#affiliated-business-form`);
  const removeAffiliatedBusinessButton = document.querySelector(`#remove-business-button`);
  const addAffiliatedBusinessButton = document.querySelector(`#add-business-button`);

  toggleElement(affiliatedBusinessForm, false);
  toggleElement(removeAffiliatedBusinessButton, false);
  toggleElement(addAffiliatedBusinessButton, true);

  // Empty form Content
  const affiliatedBusinessName = document.querySelector(`#affiliated-business-name`);
  const affiliatedBusinessAddress = document.querySelector(`#affiliated-business-address`);
  const affiliatedBusinessTableQuantity = document.querySelector(`#affiliated-business-table-quantity`);
  const affiliatedBusinessSubscriptionStatus = document.querySelector(`#affiliated-business-subcription-status`);
  
  affiliatedBusinessName.value = "";
  affiliatedBusinessAddress.innerText = "";
  affiliatedBusinessTableQuantity.value = ""; 
  affiliatedBusinessSubscriptionStatus.value = "active";
}