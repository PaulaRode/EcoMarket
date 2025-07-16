<?php
// Informações dinâmicas da empresa
$empresa = [
    'nome' => 'EcoMarket',
    'ano_fundacao' => 2025,
    'slogan' => 'Sustentabilidade que transforma o futuro',
    'email' => 'contato@ecomarket.com.br',
    'telefone' => '(51) 99999-9999'
];

$redes_sociais = [
    'facebook' => 'https://facebook.com/ecomarket',
    'twitter' => 'https://twitter.com/ecomarket',
    'whatsapp' => 'https://wa.me/5511999999999',
    'instagram' => 'https://instagram.com/ecomarket'
];

$valores = [
    [
        'icone' => '♻️',
        'titulo' => 'Sustentabilidade',
        'descricao' => 'Priorizamos produtos que respeitam o meio ambiente e promovem práticas ecológicas.'
    ],
    [
        'icone' => '🤝',
        'titulo' => 'Comunidade',
        'descricao' => 'Valorizamos parcerias locais e apoiamos produtores que fazem a diferença em suas comunidades.'
    ],
    [
        'icone' => '🌱',
        'titulo' => 'Qualidade',
        'descricao' => 'Garantimos produtos de alta qualidade que atendem aos mais rigorosos padrões sustentáveis.'
    ],
    [
        'icone' => '💚',
        'titulo' => 'Transparência',
        'descricao' => 'Mantemos total transparência sobre a origem e processos de produção de nossos produtos.'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - <?php echo $empresa['nome']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 60px 0;
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.1), rgba(144, 238, 144, 0.1));
            border-radius: 20px;
            margin-bottom: 40px;
            border: 2px solid #2e8b57;
        }

        .header h1 {
            font-size: 3.5em;
            color: #2e8b57;
            margin-bottom: 10px;
            animation: fadeInUp 1s ease-out;
            text-shadow: 2px 2px 4px rgba(46, 139, 87, 0.1);
        }

        .header p {
            font-size: 1.3em;
            color: #666;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .about-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(46, 139, 87, 0.1);
            transform: translateY(20px);
            animation: slideUp 0.8s ease-out forwards;
            border: 1px solid #e0e0e0;
            border-left: 5px solid #2e8b57;
        }

        .about-card:nth-child(2) {
            animation-delay: 0.3s;
        }

        .about-card h2 {
            color: #2e8b57;
            font-size: 2.2em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .about-card h2::before {
            content: '🌱';
            font-size: 1.2em;
        }

        .about-card p {
            color: #666;
            margin-bottom: 18px;
            font-size: 1.1em;
        }

        .mission-card {
            grid-column: 1 / -1;
            background: #ffffff;
            border: 3px solid #2e8b57;
            padding: 50px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(46, 139, 87, 0.15);
            animation: slideUp 0.8s ease-out 0.6s both;
        }

        .mission-card h2 {
            font-size: 2.8em;
            margin-bottom: 25px;
            color: #2e8b57;
            text-shadow: 2px 2px 4px rgba(46, 139, 87, 0.1);
        }

        .mission-card p {
            font-size: 1.4em;
            max-width: 800px;
            margin: 0 auto;
            color: #555;
        }

        .social-media {
            background: #ffffff;
            padding: 50px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(46, 139, 87, 0.1);
            animation: slideUp 0.8s ease-out 0.9s both;
            border: 1px solid #e0e0e0;
        }

        .social-media h2 {
            color: #2e8b57;
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .social-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8em;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 3px solid white;
        }

        .social-icon:hover {
            transform: translateY(-8px) scale(1.1);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .facebook { background: #1877f2; }
        .twitter { background: #000000; }
        .whatsapp { background: #25d366; }
        .instagram { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }

        .values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .value-item {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
            animation: fadeIn 0.8s ease-out;
        }

        .value-item:hover {
            transform: translateY(-8px);
            border-color: #2e8b57;
            box-shadow: 0 10px 25px rgba(46, 139, 87, 0.15);
        }

        .value-item h3 {
            color: #2e8b57;
            font-size: 1.4em;
            margin-bottom: 15px;
        }

        .value-item p {
            color: #666;
            font-size: 1em;
            line-height: 1.6;
        }

        .contact-info {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            border: 2px solid #2e8b57;
            margin-bottom: 40px;
            animation: slideUp 0.8s ease-out 1.2s both;
        }

        .contact-info h2 {
            color: #2e8b57;
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        .contact-info p {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .contact-info a {
            color: #2e8b57;
            text-decoration: none;
            font-weight: bold;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .header h1 {
                font-size: 2.5em;
            }
            
            .social-icons {
                gap: 20px;
            }
            
            .social-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5em;
            }
            
            .values {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <img src="assests/logo.png" alt="Logo EcoMarket" style="height: 60px; vertical-align: middle; margin-right: 15px;">
                <?php echo $empresa['nome']; ?>
            </h1>
            <p><?php echo $empresa['slogan']; ?></p>
        </div>

        <div class="content">
            <div class="about-card">
                <h2>Quem Somos</h2>
                <p>O <?php echo $empresa['nome']; ?> é mais do que um marketplace - somos uma comunidade comprometida com a sustentabilidade e o cuidado com nosso planeta.</p>
                <p>Conectamos consumidores conscientes a produtos ecológicos, orgânicos e sustentáveis, promovendo um estilo de vida mais verde e responsável.</p>
                <p>Cada compra em nossa plataforma é um passo em direção a um futuro mais sustentável para todos.</p>
            </div>

            <div class="about-card">
                <h2>Nossa História</h2>
                <p>Fundado em <?php echo $empresa['ano_fundacao']; ?>, o <?php echo $empresa['nome']; ?> nasceu da necessidade de criar uma ponte entre produtores sustentáveis e consumidores conscientes.</p>
                <p>Começamos com uma visão simples: tornar produtos ecológicos acessíveis e facilitar escolhas que beneficiam tanto as pessoas quanto o planeta.</p>
                <p>Hoje, somos uma plataforma em crescimento que apoia pequenos produtores locais e empresas comprometidas com práticas sustentáveis.</p>
            </div>

            <div class="mission-card">
                <h2>🌍 Nossa Missão</h2>
                <p>Democratizar o acesso a produtos sustentáveis, criando uma economia circular que beneficia produtores, consumidores e meio ambiente. Acreditamos que pequenas mudanças nos hábitos de consumo podem gerar grandes transformações no mundo.</p>
            </div>
        </div>

        <div class="values">
            <?php foreach ($valores as $valor): ?>
            <div class="value-item">
                <h3><?php echo $valor['icone']; ?> <?php echo $valor['titulo']; ?></h3>
                <p><?php echo $valor['descricao']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="contact-info">
            <h2>📞 Entre em Contato</h2>
            <p>Email: <a href="mailto:<?php echo $empresa['email']; ?>"><?php echo $empresa['email']; ?></a></p>
            <p>Telefone: <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', $empresa['telefone']); ?>"><?php echo $empresa['telefone']; ?></a></p>
        </div>

        <div class="social-media">
            <h2>Conecte-se Conosco</h2>
            <div class="social-icons">
                <a href="<?php echo $redes_sociais['facebook']; ?>" class="social-icon facebook" title="Facebook" target="_blank">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="<?php echo $redes_sociais['twitter']; ?>" class="social-icon twitter" title="X (Twitter)" target="_blank">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <a href="<?php echo $redes_sociais['whatsapp']; ?>" class="social-icon whatsapp" title="WhatsApp" target="_blank">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.531 3.538z"/>
                    </svg>
                </a>
                <a href="<?php echo $redes_sociais['instagram']; ?>" class="social-icon instagram" title="Instagram" target="_blank">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>