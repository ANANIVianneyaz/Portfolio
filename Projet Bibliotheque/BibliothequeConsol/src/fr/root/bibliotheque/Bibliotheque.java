package fr.root.bibliotheque;

import java.io.*;
import java.util.ArrayList;
import java.util.List;

public class Bibliotheque {
    private List<Livre> livres;
    private List<Utilisateur> utilisateurs;
    private static final String LIVRES_FILE = "livres.txt";
    private static final String UTILISATEURS_FILE = "utilisateurs.txt";

    public Bibliotheque() {
        this.livres = new ArrayList<>();
        this.utilisateurs = new ArrayList<>();
        chargerLivres();
        chargerUtilisateurs();
    }

    public void chargerLivres() {
        try (BufferedReader reader = new BufferedReader(new FileReader(LIVRES_FILE))) {
            String line;
            while ((line = reader.readLine()) != null) {
                livres.add(Livre.fromFileString(line));
            }
        } catch (IOException e) {
            System.out.println("Aucun fichier de livres trouvé. Un nouveau fichier sera créé.");
        }
    }

    public void sauvegarderLivres() {
        try (BufferedWriter writer = new BufferedWriter(new FileWriter(LIVRES_FILE))) {
            for (Livre livre : livres) {
                writer.write(livre.toFileString());
                writer.newLine();
            }
        } catch (IOException e) {
            System.out.println("Erreur lors de la sauvegarde des livres.");
        }
    }

    public void chargerUtilisateurs() {
        try (BufferedReader reader = new BufferedReader(new FileReader(UTILISATEURS_FILE))) {
            String line;
            while ((line = reader.readLine()) != null) {
                utilisateurs.add(Utilisateur.fromFileString(line));
            }
        } catch (IOException e) {
            // Ajouter un admin par défaut si le fichier n'existe pas
            utilisateurs.add(new Utilisateur("admin", "admin123", "admin"));
            utilisateurs.add(new Utilisateur("user", "user123", "user"));
            System.out.println("Aucun fichier d'utilisateurs trouvé. Des utilisateurs par défaut ont été créés.");
        }
    }

    public void sauvegarderUtilisateurs() {
        try (BufferedWriter writer = new BufferedWriter(new FileWriter(UTILISATEURS_FILE))) {
            for (Utilisateur utilisateur : utilisateurs) {
                writer.write(utilisateur.toFileString());
                writer.newLine();
            }
        } catch (IOException e) {
            System.out.println("Erreur lors de la sauvegarde des utilisateurs.");
        }
    }

    public Utilisateur connecter(String username, String password) {
        for (Utilisateur utilisateur : utilisateurs) {
            if (utilisateur.getUsername().equals(username) && utilisateur.verifierMotDePasse(password)) {
                return utilisateur;
            }
        }
        return null;
    }

    public void ajouterLivre(Livre livre) {
        livres.add(livre);
        System.out.println("Livre ajouté : " + livre);
    }

    public void afficherLivres() {
        if (livres.isEmpty()) {
            System.out.println("Aucun livre disponible.");
        } else {
            for (Livre livre : livres) {
                System.out.println(livre);
            }
        }
    }

    public Livre rechercherLivreParTitre(String titre) {
        for (Livre livre : livres) {
            if (livre.getTitre().equalsIgnoreCase(titre)) {
                return livre;
            }
        }
        return null;
    }

    public boolean supprimerLivre(int id) {
        Livre livreASupprimer = null;
        for (Livre livre : livres) {
            if (livre.getId() == id) {
                livreASupprimer = livre;
                break;
            }
        }
        if (livreASupprimer != null) {
            livres.remove(livreASupprimer);
            return true;
        }
        return false;
    }

    public boolean modifierLivre(int id, String nouveauTitre, String nouvelAuteur) {
        for (Livre livre : livres) {
            if (livre.getId() == id) {
                livre = new Livre(id, nouveauTitre, nouvelAuteur);
                return true;
            }
        }
        return false;
    }

    public void ajouterUtilisateur(Utilisateur utilisateur) {
        utilisateurs.add(utilisateur);
        System.out.println("Utilisateur ajouté : " + utilisateur.getUsername());
    }

    public List<Livre> getLivres() {
        return livres;
    }

    public List<Utilisateur> getUtilisateurs() {
        return utilisateurs;
    }
}