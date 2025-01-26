package fr.root.bibliotheque;

public class Utilisateur {
    private String username;
    private String password;
    private String role; // "admin" ou "user"

    public Utilisateur(String username, String password, String role) {
        this.username = username;
        this.password = password;
        this.role = role;
    }

    public String getUsername() {
        return username;
    }

    public boolean verifierMotDePasse(String password) {
        return this.password.equals(password);
    }

    public String getRole() {
        return role;
    }

    // Convertir un utilisateur en une ligne de texte
    public String toFileString() {
        return username + ";" + password + ";" + role;
    }

    // Créer un utilisateur à partir d'une ligne de texte
    public static Utilisateur fromFileString(String line) {
        String[] parts = line.split(";");
        String username = parts[0];
        String password = parts[1];
        String role = parts[2];
        return new Utilisateur(username, password, role);
    }
}