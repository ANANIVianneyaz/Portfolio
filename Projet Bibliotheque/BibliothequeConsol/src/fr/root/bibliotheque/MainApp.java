package fr.root.bibliotheque;

import java.util.Scanner;

public class MainApp {
    public static void main(String[] args) {
        Bibliotheque bibliotheque = new Bibliotheque();
        Scanner scanner = new Scanner(System.in);

        System.out.println("====================================");
        System.out.println("  Gestion de Bibliothèque en Console");
        System.out.println("====================================");

        // Connexion
        System.out.print("Nom d'utilisateur : ");
        String username = scanner.nextLine();
        System.out.print("Mot de passe : ");
        String password = scanner.nextLine();

        Utilisateur utilisateur = bibliotheque.connecter(username, password);
        if (utilisateur != null) {
            System.out.println("\nConnexion réussie ! Bienvenue, " + username + ".");
            boolean running = true;
            while (running) {
                if (utilisateur.getRole().equals("admin")) {
                    afficherMenuAdmin();
                } else {
                    afficherMenuUtilisateur();
                }
                System.out.print("Choisissez une option : ");
                int choix = scanner.nextInt();
                scanner.nextLine(); // Pour consommer la nouvelle ligne

                if (utilisateur.getRole().equals("admin")) {
                    switch (choix) {
                        case 1:
                            ajouterLivre(bibliotheque, scanner);
                            break;
                        case 2:
                            afficherLivres(bibliotheque);
                            break;
                        case 3:
                            rechercherLivre(bibliotheque, scanner);
                            break;
                        case 4:
                            supprimerLivre(bibliotheque, scanner);
                            break;
                        case 5:
                            modifierLivre(bibliotheque, scanner);
                            break;
                        case 6:
                            ajouterUtilisateur(bibliotheque, scanner);
                            break;
                        case 7:
                            running = false;
                            System.out.println("\nMerci d'avoir utilisé notre système. À bientôt !");
                            break;
                        default:
                            System.out.println("\nOption invalide. Veuillez réessayer.");
                    }
                } else {
                    switch (choix) {
                        case 1:
                            afficherLivres(bibliotheque);
                            break;
                        case 2:
                            rechercherLivre(bibliotheque, scanner);
                            break;
                        case 3:
                            running = false;
                            System.out.println("\nMerci d'avoir utilisé notre système. À bientôt !");
                            break;
                        default:
                            System.out.println("\nOption invalide. Veuillez réessayer.");
                    }
                }
            }
            // Sauvegarder les données avant de quitter
            bibliotheque.sauvegarderLivres();
            bibliotheque.sauvegarderUtilisateurs();
        } else {
            System.out.println("\nÉchec de la connexion. Nom d'utilisateur ou mot de passe incorrect.");
        }

        scanner.close();
    }

    private static void afficherMenuAdmin() {
        System.out.println("\n--- Menu Administrateur ---");
        System.out.println("1. Ajouter un livre");
        System.out.println("2. Afficher tous les livres");
        System.out.println("3. Rechercher un livre par titre");
        System.out.println("4. Supprimer un livre");
        System.out.println("5. Modifier un livre");
        System.out.println("6. Ajouter un utilisateur");
        System.out.println("7. Quitter");
    }

    private static void afficherMenuUtilisateur() {
        System.out.println("\n--- Menu Utilisateur ---");
        System.out.println("1. Afficher tous les livres");
        System.out.println("2. Rechercher un livre par titre");
        System.out.println("3. Quitter");
    }

    private static void ajouterLivre(Bibliotheque bibliotheque, Scanner scanner) {
        System.out.println("\n--- Ajouter un Livre ---");
        System.out.print("Titre du livre : ");
        String titre = scanner.nextLine();
        System.out.print("Auteur du livre : ");
        String auteur = scanner.nextLine();
        int id = bibliotheque.getLivres().size() + 1; // Génère un ID simple
        bibliotheque.ajouterLivre(new Livre(id, titre, auteur));
        System.out.println("Livre ajouté avec succès !");
    }

    private static void afficherLivres(Bibliotheque bibliotheque) {
        System.out.println("\n--- Liste des Livres ---");
        bibliotheque.afficherLivres();
    }

    private static void rechercherLivre(Bibliotheque bibliotheque, Scanner scanner) {
        System.out.println("\n--- Rechercher un Livre ---");
        System.out.print("Entrez le titre du livre à rechercher : ");
        String titreRecherche = scanner.nextLine();
        Livre livreTrouve = bibliotheque.rechercherLivreParTitre(titreRecherche);
        if (livreTrouve != null) {
            System.out.println("Livre trouvé : " + livreTrouve);
        } else {
            System.out.println("Aucun livre trouvé avec ce titre.");
        }
    }

    private static void supprimerLivre(Bibliotheque bibliotheque, Scanner scanner) {
        System.out.println("\n--- Supprimer un Livre ---");
        System.out.print("Entrez l'ID du livre à supprimer : ");
        int id = scanner.nextInt();
        scanner.nextLine(); // Pour consommer la nouvelle ligne
        if (bibliotheque.supprimerLivre(id)) {
            System.out.println("Livre supprimé avec succès !");
        } else {
            System.out.println("Aucun livre trouvé avec cet ID.");
        }
    }

    private static void modifierLivre(Bibliotheque bibliotheque, Scanner scanner) {
        System.out.println("\n--- Modifier un Livre ---");
        System.out.print("Entrez l'ID du livre à modifier : ");
        int id = scanner.nextInt();
        scanner.nextLine(); // Pour consommer la nouvelle ligne
        System.out.print("Nouveau titre : ");
        String nouveauTitre = scanner.nextLine();
        System.out.print("Nouvel auteur : ");
        String nouvelAuteur = scanner.nextLine();
        if (bibliotheque.modifierLivre(id, nouveauTitre, nouvelAuteur)) {
            System.out.println("Livre modifié avec succès !");
        } else {
            System.out.println("Aucun livre trouvé avec cet ID.");
        }
    }

    private static void ajouterUtilisateur(Bibliotheque bibliotheque, Scanner scanner) {
        System.out.println("\n--- Ajouter un Utilisateur ---");
        System.out.print("Nom d'utilisateur : ");
        String username = scanner.nextLine();
        System.out.print("Mot de passe : ");
        String password = scanner.nextLine();
        System.out.print("Rôle (admin/user) : ");
        String role = scanner.nextLine();
        bibliotheque.ajouterUtilisateur(new Utilisateur(username, password, role));
        System.out.println("Utilisateur ajouté avec succès !");
    }
}