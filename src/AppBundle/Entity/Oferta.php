<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Util\Slugger;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfertaRepository")
 */
class Oferta
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     *  @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     *  @Assert\NotBlank()
     */
    protected $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="condiciones", type="text")
     */
    protected $condiciones;

    /**
     * @var string
     *
     * @ORM\Column(name="rutaFoto", type="string", length=255)
     */
    protected $rutaFoto;

    /**
    * @Assert\Image(maxSize = "500k")
    */
    protected $foto;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=10, scale=2)
     * @Assert\Range(min = 0)
     */
    protected $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="descuento", type="decimal", precision=10, scale=2)
     */
    protected $descuento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaPublicacion", type="datetime")
     * @Assert\DateTime
     */
    protected $fechaPublicacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaExpiracion", type="datetime")
     * @Assert\DateTime
     */
    protected $fechaExpiracion;

    /**
     * @var int
     *
     * @ORM\Column(name="compras", type="integer")
     */
    protected $compras;

    /**
     * @var int
     *
     * @ORM\Column(name="umbral", type="integer")
     * @Assert\Range(min = 0)
     */
    protected $umbral;

    /**
     * @var bool
     *
     * @ORM\Column(name="revisada", type="boolean")
     */
    protected $revisada;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ciudad")
     */
    protected $ciudad;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tienda")
     */
    protected $tienda;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Oferta
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->slug = Slugger::getslug($nombre);
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Oferta
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Oferta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set condiciones
     *
     * @param string $condiciones
     *
     * @return Oferta
     */
    public function setCondiciones($condiciones)
    {
        $this->condiciones = $condiciones;

        return $this;
    }

    /**
     * Get condiciones
     *
     * @return string
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * Set rutFoto
     *
     * @param string $rutaFoto
     *
     * @return Oferta
     */
    public function setRutaFoto($rutaFoto = null)
    {
        $this->rutaFoto = $rutaFoto;
    }

    /**
     * Get rutaFoto
     *
     * @return string
     */
    public function getRutaFoto()
    {
        return $this->rutaFoto;
    }

    public function setFoto(UploadedFile $foto = null)
    {
    $this->foto = $foto;
    }

    /**
    * @return UploadedFile
    */
    public function getFoto()
    {
    return $this->foto;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return Oferta
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set descuento
     *
     * @param string $descuento
     *
     * @return Oferta
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return string
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set fechaPublicacion
     *
     * @param \DateTime $fechaPublicacion
     *
     * @return Oferta
     */
    public function setFechaPublicacion($fechaPublicacion)
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    /**
     * Get fechaPublicacion
     *
     * @return \DateTime
     */
    public function getFechaPublicacion()
    {
        return $this->fechaPublicacion;
    }

    /**
     * Set fechaExpiracion
     *
     * @param \DateTime $fechaExpiracion
     *
     * @return Oferta
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;

        return $this;
    }

    /**
     * Get fechaExpiracion
     *
     * @return \DateTime
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * Set compras
     *
     * @param integer $compras
     *
     * @return Oferta
     */
    public function setCompras($compras)
    {
        $this->compras = $compras;

        return $this;
    }

    /**
     * Get compras
     *
     * @return int
     */
    public function getCompras()
    {
        return $this->compras;
    }

    /**
     * Set umbral
     *
     * @param integer $umbral
     *
     * @return Oferta
     */
    public function setUmbral($umbral)
    {
        $this->umbral = $umbral;

        return $this;
    }

    /**
     * Get umbral
     *
     * @return int
     */
    public function getUmbral()
    {
        return $this->umbral;
    }

    /**
     * Set revisada
     *
     * @param boolean $revisada
     *
     * @return Oferta
     */
    public function setRevisada($revisada)
    {
        $this->revisada = $revisada;

        return $this;
    }

    /**
     * Get revisada
     *
     * @return bool
     */
    public function getRevisada()
    {
        return $this->revisada;
    }

    /**
     * Set ciudad_id
     *
     * @param string $ciudad
     *
     * @return Oferta
     */
    public function setCiudad(\AppBundle\Entity\Ciudad $ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad_id
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set tienda_id
     *
     * @param string $tienda
     *
     * @return Oferta
     */
    public function setTienda(\AppBundle\Entity\Tienda $tienda)
    {
        $this->tienda = $tienda;

        return $this;
    }

    /**
     * Get tienda_id
     *
     * @return string
     */
    public function getTienda()
    {
        return $this->tienda;
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
    * @Assert\True(message = "La fecha de expiración debe ser posterior a
    * la fecha de publicación")
    */
    public function isFechaValida()
    {
        if ($this->fechaPublicacion == null||$this->fechaExpiracion == null) 
        {
            return true;
        }
        return $this->fechaExpiracion > $this->fechaPublicacion;
    }

    public function subirFoto($directorioDestino)
    {
        if (null === $this->foto) 
        {
            return;
        }           

        $nombreArchivoFoto = uniqid('cupon-').'-foto1.jpg';
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        $this->setRutaFoto($nombreArchivoFoto);
    }
}

