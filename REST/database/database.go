package database

import (
	"database/sql"
	"log"

	_ "github.com/go-sql-driver/mysql"
)

var DB *sql.DB

func Connect() {
	var err error
	DB, err = sql.Open("mysql", "mglsi_user:passer@tcp(localhost:3306)/mglsi_news")
	if err != nil {
		log.Fatal("Failed to connect to the database:", err)
	}

	if err = DB.Ping(); err != nil {
		log.Fatal("Failed to ping the database:", err)
	}

	log.Println("Database connection established")
}
