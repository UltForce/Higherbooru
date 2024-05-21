// src/store/actions/photoActions.js
export const setPhotos = (photos) => ({
  type: "SET_PHOTOS",
  payload: photos,
});

export const addPhoto = (photo) => ({
  type: "ADD_PHOTO",
  payload: photo,
});

export const editPhoto = (index, photo) => ({
  type: "EDIT_PHOTO",
  payload: { index, photo },
});

export const deletePhoto = (index) => ({
  type: "DELETE_PHOTO",
  payload: index,
});
