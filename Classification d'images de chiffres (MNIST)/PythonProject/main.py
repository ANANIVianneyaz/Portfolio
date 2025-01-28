import tensorflow as tf
from tensorflow.keras import layers, models
import numpy as np
import matplotlib.pyplot as plt

# Charger le dataset MNIST
mnist = tf.keras.datasets.mnist
(x_train, y_train), (x_test, y_test) = mnist.load_data()

# Normaliser les valeurs des pixels entre 0 et 1
x_train, x_test = x_train / 255.0, x_test / 255.0

# Reshape des données pour qu'elles aient la forme (28, 28, 1)
x_train = x_train.reshape(-1, 28, 28, 1)
x_test = x_test.reshape(-1, 28, 28, 1)

# Construire le modèle
model = models.Sequential([
    layers.Conv2D(32, (3, 3), activation='relu', input_shape=(28, 28, 1)),
    layers.MaxPooling2D((2, 2)),
    layers.Conv2D(64, (3, 3), activation='relu'),
    layers.MaxPooling2D((2, 2)),
    layers.Conv2D(64, (3, 3), activation='relu'),
    layers.Flatten(),
    layers.Dense(64, activation='relu'),
    layers.Dense(10, activation='softmax')
])

# Compiler le modèle
model.compile(optimizer='adam',
              loss='sparse_categorical_crossentropy',
              metrics=['accuracy'])

# Entraîner le modèle
history = model.fit(x_train, y_train, epochs=5, validation_data=(x_test, y_test))

# Évaluer le modèle
test_loss, test_acc = model.evaluate(x_test, y_test, verbose=2)
print(f'\nAccuracy on test data: {test_acc:.4f}')

# Fonction pour afficher les prédictions
def plot_predictions(images, labels, predictions, num_images=9):
    """
    Affiche une grille d'images avec leurs prédictions et les étiquettes réelles.
    """
    plt.figure(figsize=(10, 10))
    for i in range(num_images):
        plt.subplot(3, 3, i+1)
        plt.imshow(images[i].reshape(28, 28), cmap='gray')
        predicted_label = np.argmax(predictions[i])
        true_label = labels[i]
        plt.title(f"Pred: {predicted_label}, True: {true_label}")
        plt.axis('off')
    plt.tight_layout()
    plt.show()

# Fonction pour afficher les images mal classées
def plot_misclassified(images, labels, predictions, num_images=9):
    """
    Affiche les images mal classées par le modèle.
    """
    misclassified_indices = np.where(np.argmax(predictions, axis=1) != labels)[0]
    misclassified_indices = misclassified_indices[:num_images]  # Limiter le nombre d'images

    plt.figure(figsize=(10, 10))
    for i, idx in enumerate(misclassified_indices):
        plt.subplot(3, 3, i+1)
        plt.imshow(images[idx].reshape(28, 28), cmap='gray')
        predicted_label = np.argmax(predictions[idx])
        true_label = labels[idx]
        plt.title(f"Pred: {predicted_label}, True: {true_label}")
        plt.axis('off')
    plt.tight_layout()
    plt.show()

# Fonction pour visualiser les filtres du modèle
def visualize_filters(model, layer_index=0):
    """
    Visualise les filtres de la première couche convolutive.
    """
    layer = model.layers[layer_index]
    filters, biases = layer.get_weights()
    num_filters = filters.shape[3]

    plt.figure(figsize=(10, 10))
    for i in range(num_filters):
        plt.subplot(6, 6, i+1)
        plt.imshow(filters[:, :, 0, i], cmap='gray')
        plt.axis('off')
    plt.tight_layout()
    plt.show()

# Fonction pour tester manuellement une image
def interactive_test(model, x_test, y_test):
    """
    Permet à l'utilisateur de sélectionner une image et de voir la prédiction du modèle.
    """
    index = int(input("Entrez un index (0 à 9999) pour tester une image : "))
    if index < 0 or index >= len(x_test):
        print("Index invalide. Veuillez entrer un index entre 0 et 9999.")
        return

    image = x_test[index]
    label = y_test[index]
    prediction = model.predict(image[np.newaxis, ...])

    plt.imshow(image.reshape(28, 28), cmap='gray')
    plt.title(f"Pred: {np.argmax(prediction)}, True: {label}")
    plt.axis('off')
    plt.show()

# Fonction pour afficher les performances du modèle
def plot_performance(history):
    """
    Affiche les courbes de perte et de précision pendant l'entraînement.
    """
    plt.figure(figsize=(12, 5))

    # Courbe de précision
    plt.subplot(1, 2, 1)
    plt.plot(history.history['accuracy'], label='Train Accuracy')
    plt.plot(history.history['val_accuracy'], label='Validation Accuracy')
    plt.title('Accuracy')
    plt.xlabel('Epoch')
    plt.ylabel('Accuracy')
    plt.legend()

    # Courbe de perte
    plt.subplot(1, 2, 2)
    plt.plot(history.history['loss'], label='Train Loss')
    plt.plot(history.history['val_loss'], label='Validation Loss')
    plt.title('Loss')
    plt.xlabel('Epoch')
    plt.ylabel('Loss')
    plt.legend()

    plt.tight_layout()
    plt.show()

# Faire des prédictions sur les données de test
predictions = model.predict(x_test)

# Afficher les prédictions
plot_predictions(x_test, y_test, predictions)

# Afficher les images mal classées
plot_misclassified(x_test, y_test, predictions)

# Visualiser les filtres de la première couche convolutive
visualize_filters(model, layer_index=0)

# Tester une image manuellement
interactive_test(model, x_test, y_test)

# Afficher les performances du modèle
plot_performance(history)