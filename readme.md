# Technical Task: Elementor Book Widget Plugin

## Goal
Create a WordPress plugin that adds a custom Elementor widget capable of rendering book data sourced from an uploaded CSV or JSON file.

## Features

### 1. Data Source
*   The widget includes a file upload control in the Elementor editor panel.
*   Supports **CSV** or **JSON** file formats.
*   **Data Structure:** Each record in the file represents a book with the following information:
    *   **Book Name**
    *   **Author Name**
    *   **Release Date**
    *   **Book Cover Image** (URL or Path)

### 2. Search Functionality
*   The widget renders a "Search Field" on the frontend.
*   Users can search/filter the displayed list by **Book Name** or **Author Name**.

### 3. Sorting Options
*   Users can sort the book list by:
    *   **Name**
    *   **Author**
    *   **Release Date**

## Data Example

### JSON Format
```json
[
  {
    "book_name": "The Great Gatsby",
    "author_name": "F. Scott Fitzgerald",
    "release_date": "1925-04-10",
    "cover_image": "https://example.com/gatsby.jpg"
  },
  {
    "book_name": "1984",
    "author_name": "George Orwell",
    "release_date": "1949-06-08",
    "cover_image": "https://example.com/1984.jpg"
  }
]
```

### CSV Format
```csv
book_name,author_name,release_date,cover_image
"The Great Gatsby","F. Scott Fitzgerald","1925-04-10","https://example.com/gatsby.jpg"
"1984","George Orwell","1949-06-08","https://example.com/1984.jpg"
```# Technical Task: Elementor Book Widget Plugin

## Goal
Create a WordPress plugin that adds a custom Elementor widget capable of rendering book data sourced from an uploaded CSV or JSON file.

## Features

### 1. Data Source
*   The widget includes a file upload control in the Elementor editor panel.
*   Supports **CSV** or **JSON** file formats.
*   **Data Structure:** Each record in the file represents a book with the following information:
    *   **Book Name**
    *   **Author Name**
    *   **Release Date**
    *   **Book Cover Image** (URL or Path)

### 2. Search Functionality
*   The widget renders a "Search Field" on the frontend.
*   Users can search/filter the displayed list by **Book Name** or **Author Name**.

### 3. Sorting Options
*   Users can sort the book list by:
    *   **Name**
    *   **Author**
    *   **Release Date**

## Data Example

### JSON Format
```json
[
  {
    "book_name": "The Great Gatsby",
    "author_name": "F. Scott Fitzgerald",
    "release_date": "1925-04-10",
    "cover_image": "https://example.com/gatsby.jpg"
  },
  {
    "book_name": "1984",
    "author_name": "George Orwell",
    "release_date": "1949-06-08",
    "cover_image": "https://example.com/1984.jpg"
  }
]
```

### CSV Format
```csv
book_name,author_name,release_date,cover_image
"The Great Gatsby","F. Scott Fitzgerald","1925-04-10","https://example.com/gatsby.jpg"
"1984","George Orwell","1949-06-08","https://example.com/1984.jpg"
```