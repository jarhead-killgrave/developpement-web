"use strict";
/* variable globale du programme */
let ligne = 4 // Nombre de ligne
let colonne = 6 // Nombre de colonne
let liste = [] // liste contenant les numeros tirés aléatoirement
let carte1, carte2; // variables qui stockent les numéros de carte cliqué 
let div1, div2; // variables qui stockent les divisions cliqués
let img1, img2; // variables qui stockent les images des divisions

let coup = 0; // Nombre de coups
let score = 0; 
let paire = 0; // Nombre de paires trouvées



function choixCarte(){
	/**
	 * Fonction qui tire et mélange une liste de carte
	*/

    // tirage des cartes
    for(let i = 0; i < (ligne * colonne) / 2; i++){
        let nombre = Math.floor(Math.random() * Math.floor(32)) +1
        liste.push(nombre)
        liste.push(nombre)
    }
    // Melanger les cartes
    for(let i = liste.length - 1 ; i > 0; i -= 1){
        let j = Math.floor(Math.random() * Math.floor(i))
        let temp = liste[j];
        liste[j] = liste[i];
        liste[i] = temp
    }

}
function PlacerCarte(){
	/**
	 * Place les cartes retourner sur le plateau de jeu
	 */

    let jeu = document.getElementById("jeu")
    let largeur = parseFloat((window.getComputedStyle(jeu).width)) / colonne; // Largeur calculé par rapprot à la taille de l'écran et du nombre de colonne
    let hauteur = parseFloat((window.getComputedStyle(jeu).height)) / ligne; // hauteur calculé par rapport à la taille de l'écran et du nombre de ligne
	let taille = Math.min(largeur, hauteur) // La taille minimale à appliquer pour une meilleur optimisation
    
	//Créer une grille par rapport au nombre de ligne, de colonne et fixer leur taille
	jeu.style.gridTemplateColumns = "repeat("+ colonne + "," + taille +"px)"
	jeu.style.gridTemplateRows = "repeat("+ ligne + "," + taille +"px)"
    
	// Placement des carte dans des divisions respectives
	for(let i = 0; i < liste.length; i++){
		if (liste[i] != -1){
			let div = document.createElement("div")
			let img = document.createElement("img")
			div.setAttribute("data-carte", liste[i]) // Le numero de la carte
			div.setAttribute("data-position", i) // la position de la carte dans la liste
			div.addEventListener("click", jouer)
			img.setAttribute("src", "images/js-logo.jpg")
			img.style.width = "100%"
			div.appendChild(img)
			jeu.appendChild(div)
		}
		else{
			// Cas où la carte aurait deja été trouvé
			let div = document.createElement("div")
			div.setAttribute("data-carte", liste[i]) // Le numero de la carte
			div.setAttribute("data-position", i) // la position de la carte dans la liste
			jeu.appendChild(div)
		}
        
    }

}

function jouer(event){
	/**
	 * Fonction qui gère le click sur les divisions
	 * 
	 * 
	 */

	// Pour le premier click, la carte1 et la div1 doivent être indéfini
    if(carte1 == undefined && div1 == undefined){
		div1 = event.currentTarget;
		carte1 = div1.dataset.carte; // Recupere le numero de la carte
		img1 = div1.firstElementChild;
		img1.src = "images/" + carte1 +".jpg"
	}
	// Pour le second click, la carte2 doit être indéfini et la div1 doit être différent de la division clické
	else if(carte2 == undefined && div1 != event.currentTarget){
		coup ++  // incrémente le nombre de coup
		div2 = event.currentTarget;
		carte2 = div2.dataset.carte; // Recupere le numero de la carte
		img2 = div2.firstElementChild;
		img2.src = "images/" + carte2 +".jpg"
		
		setTimeout(CompareCarte, 1500) // Compare les carte au bout de 1.5 secondes
		
	}
}

function CompareCarte(){
	
	// Si les cartes sont égéles
	if(carte1 == carte2){
		score += 3 // augmenter le score
		paire ++  // augmenter le nombre de paire trouvé

		// Remplacer les numéros de carte par -1 dans la liste
		liste[div1.dataset.position] = -1
		liste[div2.dataset.position] = -1

		// enlever les images du plateau
		div1.removeChild(img1)
		div2.removeChild(img2)

		// enlever le captage de click par la division
		div1.removeEventListener('click', jouer)
		div2.removeEventListener('click', jouer)

	}
	// Si les carte sont différentes
	else{
		score -- // Diminuer le score

		//Retourner les cartes
		img1.src = "images/js-logo.jpg"
		img2.src = "images/js-logo.jpg"

	}

	reinitialiserVariable()

	resultat() // afficher les resultats après le coup
	fin() // Tester si la partie est terminé

}

function fin(){
	if(identique(liste) && ligne == 4 && colonne == 6){
		if(localStorage.getItem("score") && localStorage.getItem("score") < score){
			localStorage.setItem("score", score)
		}
		else{
			localStorage.setItem("score",score)
		}
	}
}

function identique(array){
	/**
	 * Teste si une liste possède tous les mêmes éléments
	 */
	for(let i = 0; i < (array.length - 1); i++){
		if(array[i] != array[i+1]){
			return false
		}
	}
	return true;
}

function afficheScore(){
	/**
	 * Gere l'affichage du meilleur score 
	 */
	if(localStorage.getItem("score")){
		if(ligne == 4 && colonne == 6){
			document.getElementById("meilleurScore").textContent =  localStorage.getItem("score")
		}
		else{
			document.getElementById("meilleurScore").textContent = "Pas de score enregistrer pour cette configuration"
		}
	}
}

function sauvegarder(){
	let fichier = {
		"coups": coup,
		"score": score,
		"paires": paire,
		"paireRestante": (liste.length / 2) - paire,
		"nbColonne": colonne,
		"nbRangees": ligne,
		"jeu": liste
	}
	localStorage.setItem("sauvegarde", JSON.stringify(fichier))
}

function charger(){
	if(localStorage.getItem("sauvegarde")){
		let fichier = JSON.parse(localStorage.getItem("sauvegarde"))
		coup = (fichier["coups"])
		score = (fichier["score"])
		paire = (fichier["paires"])
		colonne = fichier["nbColonne"]
		ligne = fichier["nbRangees"]
		liste = fichier["jeu"]

		// Demarre la partie sauvegarder
		demarage()
		resultat()
	}
}


function parametrage(event){
	/**
	 * Gere le parametrage d'une partie
	 */
	event.preventDefault();
	let formulaire = document.getElementById("parametre")
	ligne= formulaire.elements["ligne"].value
	colonne = formulaire.elements["colonne"].value
	liste = []
	
	coup = 0
	score = 0
	paire = 0

	// Demarre la partie sauvegarder
	resultat()
	demarage()
}


function resultat(){
	/**
	 * Afficher les resultats de la partie en cours
	 */
	document.getElementById("coups").textContent = coup;
	document.getElementById("score").textContent = score;
	document.getElementById("paire").textContent = paire;

}

function SupprimerPlateauJeu(){
	/**
	 * Vide le plateau de jeu
	 */
	while(document.getElementById("jeu").children.length > 0){
		document.getElementById("jeu").removeChild(document.getElementById("jeu").firstChild)
	}	
}


function reinitialiserVariable(){
	/** reinitialise les variables utiliséss pour la gestion de la partie */
	div1 = undefined
	carte1 = undefined
	img1 = undefined
	
	div2 = undefined
	carte2 = undefined
	img2 = undefined
}

function demarage(){
	/**Lance une partie */
	if(liste == [] || identique(liste)){
		choixCarte()
	}
	SupprimerPlateauJeu()
	PlacerCarte()
	afficheScore()
}

function startMemoryGame(){
	demarage()
	document.getElementById("charger").addEventListener("click", charger)
	document.getElementById("sauvegarder").addEventListener("click", sauvegarder)
	document.getElementById("parametre").addEventListener("submit", parametrage)
    
}


