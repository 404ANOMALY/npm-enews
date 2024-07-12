package models

import (
    "time"
)

type Article struct {
    ID              int       `json:"id"`
    Titre           string    `json:"titre"`
    Contenu         string    `json:"contenu"`
    DateCreation    time.Time `json:"dateCreation"`
    DateModification time.Time `json:"dateModification"`
    Categorie       int       `json:"categorie"`
}

type Categorie struct {
    ID      int    `json:"id"`
    Libelle string `json:"libelle"`
}
