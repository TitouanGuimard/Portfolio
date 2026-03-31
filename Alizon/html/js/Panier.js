function sendGet(url, onSuccess, onError) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            if (typeof onSuccess === 'function') onSuccess(xhr);
        } else {
            if (typeof onError === 'function') onError(xhr);
        }
    };
    xhr.onerror = function() {
        if (typeof onError === 'function') onError(xhr);
    };
    xhr.send();
}

function supprimerPanier(idPanier,idProd){
        if(confirm("Voulez vous supprimer cet article ?")){
        url = "modifPanier.php?Action=supprimerProduit&Panier=" + encodeURIComponent(idPanier) + "&Produit=" + encodeURIComponent(idProd)
        sendGet(url,function() { 
            location.reload(); 
        },
        function() { 
            alert('Erreur côté serveur.');
         }
        );
    }                       
}
function modifProduit(btn,idPanier,idProd){
    if(!btn){
        return;
    }
    
    if(btn.classList.contains("btn-plus")){

        url ="modifPanier.php?Action=augmenterProduit&Panier=" + encodeURIComponent(idPanier) + "&Produit=" + encodeURIComponent(idProd);
        
    }else if(btn.classList.contains("btn-moins")){
                url ="modifPanier.php?Action=reduireProduit&Panier=" + encodeURIComponent(idPanier) + "&Produit=" + encodeURIComponent(idProd);
    }
    sendGet(url,function() { 
            location.reload(); 
        },
        function() { 
            alert('Erreur côté serveur.');
         }
    );
}
function viderPanier(idPanier,idProd){
    if(confirm("Voulez vous supprimer votre Panier ?")){
        var xmlhttp = new XMLHttpRequest();
        url = "modifPanier.php?Action=supprimerPanier&Panier=" + encodeURIComponent(idPanier) + "&Produit=" + encodeURIComponent(idProd);
        sendGet(url,function() { 
            location.reload(); 
        },
        function() { 
            alert('Erreur côté serveur.');
         }
        )
    } 
}
