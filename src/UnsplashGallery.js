import React, { useState, useEffect } from "react";
import axios from "axios";
import Masonry from "react-masonry-css";

const UnsplashGallery = () => {
  const [photos, setPhotos] = useState([]);
  const [loading, setLoading] = useState(true);

  const ACCESS_KEY = "_GVGbNo6d9MZ8ciEP-xplf1DWeqkhPX_PQpbvry-RqY";

  useEffect(() => {
    fetchRandomPhotos();
  }, []);

  const fetchRandomPhotos = () => {
    setLoading(true);
    axios
      .get("https://api.unsplash.com/photos/random", {
        params: { count: 30, client_id: ACCESS_KEY },
      })
      .then((response) => {
        setPhotos(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error fetching photos:", error);
        setLoading(false);
      });
  };

  const breakpointColumnsObj = {
    default: 5,
    1100: 4,
    700: 3,
    500: 2,
  };

  return (
    <div>
      <center>
        <h1>Random Unsplash Photo Gallery</h1>
      </center>
      {loading ? (
        <center>
          <p>Loading...</p>
        </center>
      ) : (
        <Masonry
          breakpointCols={breakpointColumnsObj}
          className="my-masonry-grid"
          columnClassName="my-masonry-grid_column"
        >
          {photos.map((photo) => (
            <div key={photo.id} className="image-container">
              <img
                src={photo.urls.regular}
                alt={photo.alt_description}
                style={{
                  width: "100%",
                  display: "block",
                  borderRadius: "1rem",
                }}
              />
            </div>
          ))}
        </Masonry>
      )}
      <style jsx>{`
        .my-masonry-grid {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          margin-left: -10px;
          margin-right: 20px;
          width: auto;
        }
        .my-masonry-grid_column {
          padding-left: 30px; /* gutter size */
          background-clip: padding-box;
        }
        .my-masonry-grid_column > div {
          margin-bottom: 30px;
        }

        .image-container img {
          transition: transform 0.2s ease-in-out;
        }

        .image-container img:hover {
          transform: scale(1.05);
        }
      `}</style>
    </div>
  );
};

export default UnsplashGallery;
