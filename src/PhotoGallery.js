import React, { useState, useEffect } from "react";
import Swal from "sweetalert2";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faEdit,
  faTrash,
  faPlus,
  faSort,
} from "@fortawesome/free-solid-svg-icons";
import "./styles.css";

const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  },
});

const PhotoGallery = () => {
  const [photos, setPhotos] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [sortBy, setSortBy] = useState(null);
  const [searchQuery, setSearchQuery] = useState("");
  const [searchResults, setSearchResults] = useState([]);
  const [photosPerPage, setPhotosPerPage] = useState(8); // State for number of photos per page

  useEffect(() => {
    const storedPhotos = JSON.parse(localStorage.getItem("photos")) || [];
    setPhotos(storedPhotos);
  }, []);

  const addPhoto = () => {
    Swal.fire({
      title: "Add Photo",
      html:
        '<div class="form-floating"><input type="text" id="swal-title" placeholder="Title" class="form-control" required maxlength="32"><label for="swal-title">Title</label></div>' +
        '<div class="form-floating"><input type="text" id="swal-description" placeholder="Description" class="form-control" required maxlength="32"><label for="swal-description">Description</label></div>' +
        '<div><input type="file" id="swal-image" accept=".jpg, .jpeg, .png" class="form-control"></div>',
      showCancelButton: true,
      confirmButtonText: "Add",
      preConfirm: () => {
        const imageInput = document.getElementById("swal-image");
        const titleInput = document.getElementById("swal-title");
        const descriptionInput = document.getElementById("swal-description");

        if (
          !imageInput.files[0] ||
          !titleInput.value.trim() ||
          !descriptionInput.value.trim()
        ) {
          Swal.showValidationMessage("Please fill in all fields");
        }

        return {
          image: imageInput.files[0],
          title: titleInput.value.trim(),
          description: descriptionInput.value.trim(),
          createdOn: new Date().toLocaleString(),
          editedOn: null,
        };
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const { image, title, description, createdOn, editedOn } = result.value;

        const reader = new FileReader();
        reader.onload = () => {
          const newPhotos = [
            ...photos,
            { src: reader.result, title, description, createdOn, editedOn },
          ];
          setPhotos(newPhotos);
          localStorage.setItem("photos", JSON.stringify(newPhotos));
          Swal.fire("Added!", "Your photo has been added.", "success").then(
            (result) => {
              if (result.isConfirmed) {
                Toast.fire({
                  icon: "success",
                  title: "Photo added successfully.",
                });
              }
            }
          );
        };
        reader.readAsDataURL(image);
      }
    });
  };

  const editPhoto = (index) => {
    const photo = photos[index];

    Swal.fire({
      title: "Edit Photo",
      html:
        `<div class="form-floating"><input type="text" id="swal-title" placeholder="Title" class="form-control" value="${photo.title}" required maxlength="32"><label for="swal-title">Title</label></div>` +
        `<div class="form-floating"><input type="text" id="swal-description" placeholder="Description" class="form-control" value="${photo.description}" required maxlength="32"><label for="swal-description">Description</label></div>` +
        `<div><img src="${photo.src}" id="swal-image-preview" style="max-width: 100%; max-height: 200px; margin-bottom: 10px;"><input type="file" id="swal-image" accept=".jpg, .jpeg, .png" class="form-control"></div>`,
      showCancelButton: true,
      confirmButtonText: "Update",
      preConfirm: () => {
        const imageInput = document.getElementById("swal-image");
        const titleInput = document.getElementById("swal-title");
        const descriptionInput = document.getElementById("swal-description");

        if (!titleInput.value.trim() || !descriptionInput.value.trim()) {
          Swal.showValidationMessage("Please fill in all fields");
        }

        if (!imageInput.files[0]) {
          return {
            image: photo.src,
            title: titleInput.value.trim(),
            description: descriptionInput.value.trim(),
            editedOn: new Date().toLocaleString(),
          };
        }

        return {
          image: imageInput.files[0],
          title: titleInput.value.trim(),
          description: descriptionInput.value.trim(),
          editedOn: new Date().toLocaleString(),
        };
      },
      didOpen: () => {
        const imageInput = document.getElementById("swal-image");
        const imagePreview = document.getElementById("swal-image-preview");
        imageInput.addEventListener("change", () => {
          const file = imageInput.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = () => {
              imagePreview.src = reader.result;
            };
            reader.readAsDataURL(file);
          }
        });
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const { image, title, description, editedOn } = result.value;
        const imageData =
          typeof image === "string" ? image : URL.createObjectURL(image);
        const updatedPhotos = [...photos];
        updatedPhotos[index] = {
          ...updatedPhotos[index],
          src: imageData,
          title,
          description,
          editedOn,
        };
        setPhotos(updatedPhotos);
        localStorage.setItem("photos", JSON.stringify(updatedPhotos));
        Swal.fire("Updated!", "Your photo has been updated.", "success").then(
          (result) => {
            if (result.isConfirmed) {
              Toast.fire({
                icon: "success",
                title: "Photo updated successfully.",
              });
            }
          }
        );
      }
    });
  };

  const deletePhoto = (index) => {
    Swal.fire({
      title: "Delete Photo?",
      text: "Are you sure you want to delete this photo?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        const updatedPhotos = [...photos];
        updatedPhotos.splice(index, 1);
        setPhotos(updatedPhotos);
        localStorage.setItem("photos", JSON.stringify(updatedPhotos));
        Swal.fire("Deleted!", "Your photo has been deleted.", "success").then(
          (result) => {
            if (result.isConfirmed) {
              Toast.fire({
                icon: "success",
                title: "Photo deleted successfully.",
              });
            }
          }
        );
      }
    });
  };
  const handleSearch = (event) => {
    const query = event.target.value;
    setSearchQuery(query);
    const filteredPhotos = photos.filter((photo) => {
      return (
        photo.title.toLowerCase().includes(query.toLowerCase()) ||
        photo.description.toLowerCase().includes(query.toLowerCase())
      );
    });
    setSearchResults(filteredPhotos);
  };

  // Add state to track sort direction
  const [sortDirection, setSortDirection] = useState({
    title: "asc",
    description: "asc",
    createdOn: "asc",
  });

  // Modify handleSort function
  const handleSort = (sortType) => {
    // Toggle sort direction
    const newSortDirection = sortDirection[sortType] === "asc" ? "desc" : "asc";
    setSortDirection({ ...sortDirection, [sortType]: newSortDirection });
    setSortBy(sortType);
  };

  // Modify sortPhotos function to consider sort direction
  const sortPhotos = (a, b) => {
    switch (sortBy) {
      case "title":
        return sortDirection.title === "asc"
          ? a.title.localeCompare(b.title)
          : b.title.localeCompare(a.title);
      case "description":
        return sortDirection.description === "asc"
          ? a.description.localeCompare(b.description)
          : b.description.localeCompare(a.description);
      case "createdOn":
        return sortDirection.createdOn === "asc"
          ? new Date(a.createdOn) - new Date(b.createdOn)
          : new Date(b.createdOn) - new Date(a.createdOn);
      default:
        return 0;
    }
  };

  const handlePerPageChange = (event) => {
    // Function to handle change in photos per page
    setPhotosPerPage(parseInt(event.target.value, 10));
    setCurrentPage(1); // Reset to first page when changing photos per page
  };

  const indexOfLastPhoto = currentPage * photosPerPage;
  const indexOfFirstPhoto = indexOfLastPhoto - photosPerPage;
  const sortedPhotos = photos.slice().sort(sortPhotos);
  const currentPhotos = searchQuery
    ? searchResults.slice(indexOfFirstPhoto, indexOfLastPhoto)
    : sortedPhotos.slice(indexOfFirstPhoto, indexOfLastPhoto);

  // Calculate the number of displayed images
  const displayedImagesCount = currentPhotos.length;
  const totalImagesCount = sortedPhotos.length;

  return (
    <div className="footer-container">
      <div className="footer-content-wrapper">
        <center>
          <br />
          <button
            onClick={addPhoto}
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Add Photo"
            className=" btn btn-outline-primary"
          >
            <FontAwesomeIcon icon={faPlus} />
          </button>{" "}
          <button
            onClick={() => handleSort("title")}
            className={`sort-btn btn btn-outline-primary ${
              sortBy === "title" ? "active" : ""
            }`}
          >
            Sort by Title <FontAwesomeIcon icon={faSort} />
          </button>{" "}
          <button
            onClick={() => handleSort("description")}
            className={`sort-btn btn btn-outline-primary ${
              sortBy === "description" ? "active" : ""
            }`}
          >
            Sort by Description <FontAwesomeIcon icon={faSort} />
          </button>{" "}
          <button
            onClick={() => handleSort("createdOn")}
            className={`sort-btn btn btn-outline-primary ${
              sortBy === "createdOn" ? "active" : ""
            }`}
          >
            Sort by Creation Date <FontAwesomeIcon icon={faSort} />
          </button>{" "}
          <input
            type="text"
            placeholder="Search..."
            value={searchQuery}
            onChange={handleSearch}
          />{" "}
          Photos per page: {/* Dropdown for selecting photos per page */}
          <select
            aria-label="Select Photos Per Page"
            value={photosPerPage}
            onChange={handlePerPageChange}
          >
            <option value="4">4</option>
            <option value="8">8</option>
            <option value="12">12</option>
          </select>
          <div className="photo-gallery row row-cols-1 row-cols-md-5 g-4">
            {currentPhotos.map((photo, index) => (
              <div key={index} className="col">
                <div className="card h-100">
                  <center>
                    <img
                      src={photo.src}
                      alt={`Photo ${index + 1}`}
                      className="card-img-top"
                    />
                  </center>
                  <div className="card-body">
                    <h5 className="card-title">{photo.title}</h5>
                    <p className="card-text">{photo.description}</p>
                    <p className="card-text">
                      <small>Created on: {photo.createdOn}</small>
                    </p>
                    {photo.editedOn && (
                      <p className="card-text">
                        <small>Edited on: {photo.editedOn}</small>
                      </p>
                    )}
                    <div className="d-flex justify-content-between align-items-center">
                      <button
                        onClick={() => editPhoto(indexOfFirstPhoto + index)}
                        className="btn btn-primary"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Edit Photo"
                      >
                        <FontAwesomeIcon icon={faEdit} />
                      </button>
                      <button
                        onClick={() => deletePhoto(indexOfFirstPhoto + index)}
                        className="btn btn-danger"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Delete Photo"
                      >
                        <FontAwesomeIcon icon={faTrash} />
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
          <div>
            Displaying {displayedImagesCount} out of {totalImagesCount} images
          </div>
        </center>
        <nav aria-label="Page navigation">
          <ul className="pagination justify-content-center">
            {[
              ...Array(Math.ceil(sortedPhotos.length / photosPerPage)).keys(),
            ].map((number) => (
              <li
                key={number}
                className={`page-item ${
                  currentPage === number + 1 ? "active" : ""
                }`}
              >
                <button
                  className="page-link"
                  onClick={() => setCurrentPage(number + 1)}
                >
                  {number + 1}
                </button>
              </li>
            ))}
          </ul>
        </nav>
      </div>
    </div>
  );
};

export default PhotoGallery;
