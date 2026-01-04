<div style="max-width: 520px; margin: 60px auto; padding: 0 20px;">
    <div style="background: white; border-radius: 20px; padding: 50px 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="font-size: 56px; margin-bottom: 15px;">✨</div>
            <h2 style="margin: 0 0 10px 0; font-size: 32px; font-weight: 800; color: #2d3748; letter-spacing: -0.5px;">Créer un compte</h2>
            <p style="margin: 0; color: #718096; font-size: 16px; font-weight: 400;">Rejoignez HolyGoods dès aujourd'hui</p>
        </div>

        <?php if (isset($message)): ?>
            <div style="padding: 16px 20px; margin-bottom: 30px; border-radius: 12px;
                        background: <?= isset($success) && $success ? 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)' : 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)' ?>;
                        color: <?= isset($success) && $success ? '#155724' : '#721c24' ?>;
                        border-left: 4px solid <?= isset($success) && $success ? '#28a745' : '#dc3545' ?>;
                        font-weight: 500;">
                <?= isset($success) && $success ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register" style="display: flex; flex-direction: column; gap: 24px;">
            <div>
                <label for="nom" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; font-size: 14px;">Nom complet</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    required
                    value="<?= isset($old_values['nom']) ? htmlspecialchars($old_values['nom']) : '' ?>"
                    style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-size: 16px; transition: all 0.3s ease; background: #f7fafc;"
                    placeholder="Votre nom"
                    onfocus="this.style.borderColor='#dc3545'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(220, 53, 69, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f7fafc'; this.style.boxShadow='none'"
                >
            </div>

            <div>
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; font-size: 14px;">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="<?= isset($old_values['email']) ? htmlspecialchars($old_values['email']) : '' ?>"
                    style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-size: 16px; transition: all 0.3s ease; background: #f7fafc;"
                    placeholder="vous@example.com"
                    onfocus="this.style.borderColor='#dc3545'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(220, 53, 69, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f7fafc'; this.style.boxShadow='none'"
                >
            </div>

            <div>
                <label for="adresse" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; font-size: 14px;">Adresse</label>
                <textarea
                    id="adresse"
                    name="adresse"
                    rows="3"
                    style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-family: inherit; font-size: 16px; transition: all 0.3s ease; background: #f7fafc; resize: vertical;"
                    placeholder="Votre adresse postale"
                    onfocus="this.style.borderColor='#dc3545'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(220, 53, 69, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f7fafc'; this.style.boxShadow='none'"
                ><?= isset($old_values['adresse']) ? htmlspecialchars($old_values['adresse']) : '' ?></textarea>
            </div>

            <div>
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; font-size: 14px;">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-size: 16px; transition: all 0.3s ease; background: #f7fafc;"
                    placeholder="Au moins 6 caractères"
                    onfocus="this.style.borderColor='#dc3545'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(220, 53, 69, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f7fafc'; this.style.boxShadow='none'"
                >
            </div>

            <div>
                <label for="password_confirm" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; font-size: 14px;">Confirmer le mot de passe</label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    required
                    style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-size: 16px; transition: all 0.3s ease; background: #f7fafc;"
                    placeholder="Répétez le mot de passe"
                    onfocus="this.style.borderColor='#dc3545'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(220, 53, 69, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f7fafc'; this.style.boxShadow='none'"
                >
            </div>

            <button
                type="submit"
                style="padding: 16px 24px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; border: 2px solid #dc3545; border-radius: 12px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; margin-top: 10px;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'"
            >
                Créer mon compte
            </button>
        </form>

        <div style="margin-top: 30px; text-align: center; padding-top: 30px; border-top: 2px solid #f7fafc;">
            <p style="margin: 0; color: #718096; font-size: 15px;">
                Déjà un compte ? 
                <a href="/login" style="color: #dc3545; text-decoration: none; font-weight: 600; margin-left: 5px;" onmouseover="this.style.textDecoration='underline'; this.style.color='#c53030'" onmouseout="this.style.textDecoration='none'; this.style.color='#dc3545'">
                    Se connecter
                </a>
            </p>
        </div>
    </div>
</div>


