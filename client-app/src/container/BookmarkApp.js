import React, { useState, useEffect } from "react";
import "./BookmarkApp.css";

const apiUrl = "http://localhost:3000/api";
const BookmarkApp = () => {
  const [bookmarks, setBookmarks] = useState([]);
  const [newBookmark, setNewBookmark] = useState({ url: "", title: "" });

  useEffect(() => {
    readAllBookmarks();
  }, []);

  const addNewBookmark = async () => {
    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(newBookmark),
    };
    await fetch(apiUrl + "/create.php", options);
    setNewBookmark({ url: "", title: "" });
    readAllBookmarks();
  };

  const deleteBookmark = async (id) => {
    const options = {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id }),
    };
    await fetch(apiUrl + "/delete.php", options);
    readAllBookmarks();
  };

  const readAllBookmarks = async () => {
    try {
      const response = await fetch(apiUrl + "/readAll.php");
      const bookmarks = await response.json();
      setBookmarks(bookmarks);
    } catch (e) {
      console.error(e);
    }
  };

  return (
    <div id="content">
      <div>
        <h1> Bookmarking App</h1>
        <input
          type="text"
          placeholder="Enter bookmark URL"
          value={newBookmark.url}
          onChange={(e) =>
            setNewBookmark({ ...newBookmark, url: e.target.value })
          }
        />
        <input
          type="text"
          placeholder="Enter bookmark title"
          value={newBookmark.title}
          onChange={(e) =>
            setNewBookmark({ ...newBookmark, title: e.target.value })
          }
        />
        <button onClick={addNewBookmark}>Add Bookmark</button>
      </div>
      <ul>
        {bookmarks.map((bookmark) => (
          <li key={bookmark.id}>
            <a href={bookmark.url} rel="noopener noreferrer">
              {bookmark.title}
            </a>
            <button onClick={() => deleteBookmark(bookmark.id)}>delete</button>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default BookmarkApp;
