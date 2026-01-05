@WebServlet("/envoyer.java")
public class EnvoyerMailServlet extends HttpServlet {
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        String destinataire = request.getParameter("email");
        Part fichierPart = request.getPart("fichier");
        String nomFichier = Paths.get(fichierPart.getSubmittedFileName()).getFileName().toString();
        File fichierTemp = File.createTempFile("upload_", nomFichier);
        fichierPart.write(fichierTemp.getAbsolutePath());

        String username = "musjosue809@gmail.com";
        String password = "TON_MOT_DE_PASSE_APPLICATION_GOOGLE"; // Tu le mets ici

        try {
            SendEmail.sendMail(username, password, destinataire, fichierTemp);
            response.getWriter().write("Email envoyé avec succès !");
        } catch (Exception e) {
            e.printStackTrace();
            response.getWriter().write("Erreur : " + e.getMessage());
        }
    }
}
