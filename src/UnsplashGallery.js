import React, { useState, useEffect } from "react";
import axios from "axios";

const UnsplashGallery = () => {
  const [photos, setPhotos] = useState([]);
  const [loading, setLoading] = useState(true); // Loading state

  const ACCESS_KEY = "_GVGbNo6d9MZ8ciEP-xplf1DWeqkhPX_PQpbvry-RqY";

  useEffect(() => {
    fetchRandomPhotos();
  }, []);

  const fetchRandomPhotos = () => {
    setLoading(true); // Set loading to true when fetching starts
    axios
      .get("https://api.unsplash.com/photos/random", {
        params: {
          count: 30, // Adjust as needed
          client_id: ACCESS_KEY,
        },
      })
      .then((response) => {
        console.log("Response from Unsplash API:", response.data);
        setPhotos(response.data);
        setLoading(false); // Set loading to false when fetching completes
      })
      .catch((error) => {
        console.error("Error fetching photos:", error);
        setLoading(false); // Set loading to false in case of error
      });
  };

  return (
    <div>
      <center>
        <h1>Random Unsplash Photo Gallery</h1>
      </center>
      {loading ? ( // Show loading indicator while fetching
        <center>
          <p>Loading...</p>
        </center>
      ) : (
        <div className="photo-gallery">
          {photos.map((photo) => (
            <div key={photo.id} className="photo">
              <img src={photo.urls.regular} alt={photo.alt_description} />
            </div>
          ))}
        </div>
      )}
      <style jsx>{`
        .photo-gallery {
          display: grid;
          grid-template-columns: repeat(5, 1fr); // 5 images per row
          gap: 20px; // Adjust as needed
        }

        .photo {
          border-radius: 8px;
          overflow: hidden;
        }

        .photo img {
          width: 100%;
          height: auto;
        }
      `}</style>
    </div>
  );
};

export default UnsplashGallery;
