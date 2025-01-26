package fr.root.bibliotheque;

public class Livre {
    private String titre;
    private String auteur;
    private int id;

    public Livre(int id, String titre, String auteur) {
        this.id = id;
        this.titre = titre;
        this.auteur = auteur;
    }

    public String getTitre() {
        return titre;
    }

    public String getAuteur() {
        return auteur;
    }

    public int getId() {
        return id;
    }

    @Override
    public String toString() {
        return "Livre [id=" + id + ", titre=" + titre + ", auteur=" + auteur + "]";
    }

    // Convertir un livre en une ligne de texte
    public String toFileString() {
        return id + ";" + titre + ";" + auteur;
    }

    // Créer un livre à partir d'une ligne de texte
    public static Livre fromFileString(String line) {
        String[] parts = line.split(";");
        int id = Integer.parseInt(parts[0]);
        String titre = parts[1];
        String auteur = parts[2];
        return new Livre(id, titre, auteur);
    }
}