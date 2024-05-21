// src/reducers/photoReducer.js
const initialState = [];

const photoReducer = (state = initialState, action) => {
  switch (action.type) {
    case 'SET_PHOTOS':
      return action.payload;
    case 'ADD_PHOTO':
      return [...state, action.payload];
    case 'EDIT_PHOTO':
      const updatedPhotos = state.map((photo, index) =>
        index === action.payload.index ? action.payload.photo : photo
      );
      return updatedPhotos;
    case 'DELETE_PHOTO':
      return state.filter((_, index) => index !== action.payload);
    default:
      return state;
  }
};

export default photoReducer;
