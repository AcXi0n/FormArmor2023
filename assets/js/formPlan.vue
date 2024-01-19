<script>

    const API = ""
    export default{
        data() {
            return {
                reponseObjet : {},
                idClientSelect : -1,
                nom: '', 
                prenom: '', 
                heureInfo: 0, 
                heureBureau: 0,
            }

        },

        methods : {
            async fetchAPI() {
                await axios.get(API)
                .then((response)=>{                   

                })  

                .catch((error)=>{
                    console.log(error)
                })
            },

            onChangeClient()
            {
                console.log("hjgjhgjhhh");
                /*
                console.log('idClient : ' + this.idClient);

                var selectClient = document.getElementById("plan_formation_client");			
                idClientSelect = selectClient.options[selectClient.selectedIndex].value;
                console.log(idClientSelect);

                var divTableauClient = document.getElementById("divTableauClient");
                divTableauClient.innerHTML = "<table class='table table-striped'>"+
                                                    "<th>Nom</th>"+
                                                    "<th>Prenom</th>"+
                                                    "<th>Heure d'informatique</th>"+
                                                    "<th>Heure de bureautique</th>"+
                                                    "<tr>"+
                                                            "<td>"+nom+"</td>"+
                                                            "<td>"+prenom+"</td>"+
                                                            "<td>"+heureInfo+"</td>"+
                                                            "<td>"+heureBureau+"</td>"+
                                                    "</tr>"+
                                            "</table>";
                                            */
            },

        }

    }

</script>